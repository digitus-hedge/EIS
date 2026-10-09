<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CareerEnquiryController extends Controller
{
    /** Career > Career Enquiries (view only; search by name, email, phone, nationality or position) */
    public function index(Request $request)
    {
        $search  = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $enquiries = CareerEnquiry::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('nationality', 'like', $like)
                        ->orWhere('apply_for', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->appends(['search' => $search !== '' ? $search : null, 'per_page' => $perPage]);

        return view('admin.career.enquiries', compact('enquiries', 'search', 'perPage'));
    }

    /**
     * Opens the resume / CV attached to an enquiry (admin only).
     * A PDF is shown in the browser; a Word file is downloaded by the browser.
     * Looks on the private disk first, then on the public one.
     */
    public function cv(CareerEnquiry $enquiry)
    {
        abort_unless($enquiry->cv, 404);

        $disk = collect(['local', 'public'])->first(fn ($name) => Storage::disk($name)->exists($enquiry->cv));
        abort_unless($disk, 404);

        $extension = pathinfo($enquiry->cv, PATHINFO_EXTENSION);
        $filename  = 'CV-' . (Str::slug($enquiry->name) ?: 'applicant') . ($extension ? '.' . $extension : '');

        return Storage::disk($disk)->response($enquiry->cv, $filename);
    }
}
