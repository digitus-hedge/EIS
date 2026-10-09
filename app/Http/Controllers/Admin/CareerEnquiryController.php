<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerEnquiry;
use Illuminate\Http\Request;

class CareerEnquiryController extends Controller
{
    /** Career > Career Enquiries (view only; search by name, email, phone or position) */
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
                        ->orWhere('apply_for', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->appends(['search' => $search !== '' ? $search : null, 'per_page' => $perPage]);

        return view('admin.career.enquiries', compact('enquiries', 'search', 'perPage'));
    }
}
