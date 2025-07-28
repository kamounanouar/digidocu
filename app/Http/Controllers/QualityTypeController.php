<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QualityType;

class QualityTypeController extends Controller
{
    public function index()
    {
        $qualityTypes = QualityType::orderBy('position')->get();
        return view('quality_types.index', compact('qualityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quality_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|integer|unique:quality_types,position',
            'libel' => 'required|string|max:255',
        ]);

        QualityType::create($request->all());

        return redirect()->route('quality-types.index')
                         ->with('success', 'Quality Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityType $qualityType)
    {
        return view('quality_types.show', compact('qualityType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityType $qualityType)
    {
        return view('quality_types.edit', compact('qualityType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityType $qualityType)
    {
        $request->validate([
            'position' => 'required|integer|unique:quality_types,position,' . $qualityType->id,
            'libel' => 'required|string|max:255',
        ]);

        $qualityType->update($request->all());

        return redirect()->route('quality-types.index')
                         ->with('success', 'Quality Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityType $qualityType)
    {
        $qualityType->delete();

        return redirect()->route('quality-types.index')
                         ->with('success', 'Quality Type deleted successfully.');
    }
}
