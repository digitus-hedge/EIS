<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatRequest;
use App\Models\Stat;

class StatController extends Controller
{
    /**
     * SHOW FORM — always the single Stats section (or empty model if none exists yet)
     */
    public function index()
    {
        $stat = Stat::first() ?? new Stat(['items' => []]);
        return view('admin.stat.form', compact('stat'));
    }

    /**
     * STORE — creates the Stats section if none exists, otherwise updates the existing one
     */
    public function store(StatRequest $request)
    {
        $data = $request->validated();

        $stat = Stat::first() ?? new Stat();
        $stat->items = $data['items'];
        $stat->save();

        return redirect()
            ->route('admin.home.stats')
            ->with('success', 'Stats saved successfully.');
    }
}
