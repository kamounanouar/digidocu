<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QualityLog;
use App\Quality;
use App\QualityType;

class QualityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualityLogs = QualityLog::with('quality')
    ->orderBy('date', 'desc')
    ->get()
    ->unique('date'); // Collection method to get unique by date
        return view('quality_logs.index', compact('qualityLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer tous les types de qualité avec leurs qualités associées
        // On trie les types par position et les qualités par position puis libellé
        $qualities = QualityType::with(['qualities' => function($query) {
            $query->orderBy('position')->orderBy('libel');
        }])->orderBy('position')->get();

        return view('quality_logs.create_grouped', compact('qualities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'quality' => 'required|array',
            'quality.*.status' => 'nullable|boolean',
            'quality.*.comment' => 'nullable|string|max:1000',
            'date' => 'required|date',
        ]);

        //QualityLog::create($request->all());

        $createdLogs = [];
    
        foreach ($validated['quality'] as $quality_id => $data) {
            // Verify the quality_id exists (additional protection)
            if (!Quality::where('id', $quality_id)->exists()) {
                continue; // or throw an exception
            }
    
            $createdLogs[] = QualityLog::create([
                'quality_id' => $quality_id,
                'date' => $validated['date'],
                'status' => $data['status'] ?? false,
                'comment' => $data['comment'] ?? null,
            ]);
        }

        return redirect()->route('quality-logs.index')->with('success', 'Quality Log created successfully.');


    }

    /**
     * Display the specified resource.
     */
    public function show(QualityLog $qualityLog)
    {
        return view('quality_logs.show', compact('qualityLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityLog $qualityLog)
    {
        $qualities = Quality::all();
        return view('quality_logs.edit', compact('qualityLog', 'qualities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityLog $qualityLog)
    {
        $request->validate([
            'quality_id' => 'required|exists:qualities,id',
            'date' => 'required|date',
            'status' => 'required|boolean',
            'comment' => 'nullable|string|max:1000',
        ]);

        $qualityLog->update($request->all());

        return redirect()->route('quality-logs.index')
                         ->with('success', 'Quality Log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityLog $qualityLog)
    {
        $qualityLog->delete();

        return redirect()->route('quality-logs.index')
                         ->with('success', 'Quality Log deleted successfully.');
    }
}
