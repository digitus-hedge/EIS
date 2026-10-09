<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerRequest;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /** Career > Career List (search by title or job location, choose how many rows per page) */
    public function index(Request $request)
    {
        $search  = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $careers = Career::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)->orWhere('location', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->appends(['search' => $search !== '' ? $search : null, 'per_page' => $perPage]);

        return view('admin.career.index', compact('careers', 'search', 'perPage'));
    }

    /** "Add Career" button */
    public function create()
    {
        return view('admin.career.form', ['career' => new Career()]);
    }

    public function store(CareerRequest $request)
    {
        Career::create($request->validated());

        return redirect()->route('admin.career')->with('success', 'Career added successfully.');
    }

    public function edit(Career $career)
    {
        return view('admin.career.form', compact('career'));
    }

    public function update(CareerRequest $request, Career $career)
    {
        $career->update($request->validated());

        return redirect()->route('admin.career')->with('success', 'Career updated successfully.');
    }

    public function destroy(Career $career)
    {
        $career->delete();

        return redirect()->route('admin.career')->with('success', 'Career deleted successfully.');
    }
}
