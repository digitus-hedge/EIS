<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The single record behind Admin > Career > Banner:
 * banner title and description, banner image or video, the career section's title and
 * description, and SEO meta of the website's Career page.
 *
 * On the website:  $careerPage = \App\Models\CareerPage::first();
 */
class CareerPage extends Model
{
    protected $fillable = [
        'banner_title', 'banner_description', 'banner', 'banner_video',
        'career_title', 'career_description',
        'meta_title', 'meta_description',
    ];
}
