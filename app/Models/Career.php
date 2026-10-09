<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * One career entry: a title, a job location and a rich text description.
 * Created and edited under Admin > Career, shown on the website's career page.
 */
class Career extends Model
{
    protected $fillable = ['title', 'location', 'description'];

    /** The description is always cleaned before it is stored, so it is safe to print with {!! !!}. */
    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = static::cleanHtml($value);
    }

    /** Short plain-text version of the description, for the admin list ($career->excerpt). */
    public function getExcerptAttribute(): string
    {
        return Str::limit(static::plainText($this->description), 120);
    }

    /** Description with all tags removed. */
    public static function plainText(?string $html): string
    {
        $text = preg_replace('/<[^>]+>/', ' ', (string) $html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\u{00A0}", ' ', $text);

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * Keeps only the formatting the editor offers (paragraphs, headings, bold, italic,
     * lists, links, quotes) and drops everything else: scripts, styles, images, inline
     * event handlers, javascript: links and so on.
     */
    public static function cleanHtml(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $allowed = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'a', 'blockquote'];
        // removed together with everything inside them
        $dropWhole = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'select',
                      'textarea', 'svg', 'math', 'link', 'meta', 'base', 'img', 'picture', 'video', 'audio',
                      'figure', 'template', 'noscript', 'title', 'head'];

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML(
            '<?xml encoding="UTF-8"><div id="career-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = null;
        foreach ($doc->getElementsByTagName('div') as $div) {
            if ($div->getAttribute('id') === 'career-root') {
                $root = $div;
                break;
            }
        }
        if (! $root) {
            return e(static::plainText($html));
        }

        $walk = function (\DOMNode $node) use (&$walk, $allowed, $dropWhole) {
            foreach (iterator_to_array($node->childNodes) as $child) {
                if ($child instanceof \DOMText) {
                    continue;
                }
                if (! $child instanceof \DOMElement) {
                    $node->removeChild($child);            // comments, processing instructions
                    continue;
                }

                $tag = strtolower($child->nodeName);

                if (in_array($tag, $dropWhole, true)) {
                    $node->removeChild($child);
                    continue;
                }

                $walk($child);                             // clean the inside first

                if (! in_array($tag, $allowed, true)) {    // unknown tag: keep its text, drop the tag
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                $href = $tag === 'a' ? trim($child->getAttribute('href')) : '';
                foreach (iterator_to_array($child->attributes) as $attribute) {
                    $child->removeAttribute($attribute->nodeName);
                }
                if ($tag === 'a' && preg_match('~^(https?://|mailto:|tel:|/|#)~i', $href)) {
                    $child->setAttribute('href', $href);
                    if (preg_match('~^https?://~i', $href)) {
                        $child->setAttribute('target', '_blank');
                        $child->setAttribute('rel', 'noopener noreferrer');
                    }
                }
            }
        };
        $walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }
}
