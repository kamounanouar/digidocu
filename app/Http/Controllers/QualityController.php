<?php

namespace App\Http\Controllers;

use App\QualityT1;
use App\Quality;
use App\QualityT2;
use App\QualityLogT2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualities = Quality::all();
        return view('qualities.index', compact('qualities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$qualityT1s = QualityT1::with('qualityT2s')->orderBy('position')->get();
        $qualityT1s = QualityT1::with(['qualityT2s' => function($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();

            //dd($qualities); // Debugging line to check the structure of $qualities
        return view('qualities.create', compact('qualityT1s'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'comment' => 'nullable|string|max:1000',
            'logs' => 'required|array',
            'logs.*.quality_t2_id' => 'required|exists:quality_t2,id',
            'logs.*.status' => 'nullable|boolean',
            'logs.*.comment' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Create main quality record
            $quality = Quality::create([
                'date' => $validated['date'],
                'comment' => $validated['comment'] ?? null,
            ]);

            $createdLogs = [];
            
            foreach ($validated['logs'] as $logData) {
                $qualityT2 = QualityT2::findOrFail($logData['quality_t2_id']);
                
                $createdLogs[] = QualityLogT2::create([
                    'quality_id' => $quality->id,
                    'quality_t2_id' => $qualityT2->id,
                    'status' => $logData['status'] ?? false,
                    'comment' => $logData['comment'] ?? null,
                ]);
            }

            DB::commit();

            return redirect('qualities.index')->back()->with([
                'success' => 'Quality logs created successfully',
                'quality_id' => $quality->id,
                'created_logs_count' => count($createdLogs)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating quality logs: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Eager load all necessary relationships
    $quality = Quality::with([
        'qualityLogs.qualityT2.qualityT1', // Nested relationships
        'qualityLogs' => function($query) {
            $query->orderBy('created_at', 'desc');
        }
    ])->findOrFail($id);

    // Group logs by their T1 category for better display
    $groupedLogs = $quality->qualityLogs->groupBy(function ($log) {
        return $log->qualityT2->qualityT1->libel;
    });

    return view('qualities.show', [
        'quality' => $quality,
        'groupedLogs' => $groupedLogs,
        'statusBadges' => [
            0 => 'badge bg-danger',
            1 => 'badge bg-success'
        ]
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    // app/Http/Controllers/QualityLogController.php
    public function edit($id)
    {
        $quality = Quality::with([
            'qualityLogs.qualityT2.qualityT1',
            'qualityLogs' => function($query) {
                $query->orderBy('created_at');
            }
        ])->findOrFail($id);

        // Get all available quality types for the form
        $qualityT1s = QualityT1::with(['qualityT2s' => function($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();

        // Create a map of existing logs for easy access
        $existingLogs = $quality->qualityLogs->keyBy('quality_t2_id');

        return view('qualities.edit', [
            'quality' => $quality,
            'qualityT1s' => $qualityT1s,
            'existingLogs' => $existingLogs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $quality = Quality::findOrFail($id);

    $validated = $request->validate([
        'date' => 'required|date',
        'comment' => 'nullable|string|max:1000',
        'logs' => 'required|array',
        'logs.*.quality_t2_id' => 'required|exists:quality_t2,id',
        'logs.*.status' => 'nullable|boolean',
        'logs.*.comment' => 'nullable|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
        // Update main quality record
        $quality->update([
            'date' => $validated['date'],
            'comment' => $validated['comment'] ?? null,
        ]);

        // Process each log entry
        foreach ($validated['logs'] as $logData) {
            QualityLogT2::updateOrCreate(
                [
                    'quality_id' => $quality->id,
                    'quality_t2_id' => $logData['quality_t2_id']
                ],
                [
                    'status' => $logData['status'] ?? false,
                    'comment' => $logData['comment'] ?? null,
                ]
            );
        }

        // Delete any logs that weren't included in the update
        QualityLogT2::where('quality_id', $quality->id)
            ->whereNotIn('quality_t2_id', array_keys($validated['logs']))
            ->delete();

        DB::commit();

        return redirect()->route('qualities.show', $quality->id)
            ->with('success', 'Quality log updated successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->with('error', 'Error updating quality log: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $quality = Quality::findOrFail($id);
    
    DB::beginTransaction();
    try {
        // Supprime d'abord les logs associés
        $quality->qualityLogs()->delete();
        // Puis supprime l'entrée principale
        $quality->delete();
        
        DB::commit();
        
        return redirect()->route('quality-logs.index')
            ->with('success', 'Contrôle qualité supprimé avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
}

}
