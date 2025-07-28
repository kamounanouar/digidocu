<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArticlesImport;
use App\Imports\StocksImport;

class ImportController extends Controller
{
    // Affiche le formulaire d'import
    public function showImportForm()
    {
        return view('import');
    }

    // Nouvelle méthode pour la prévisualisation
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480'
        ]);

        $file = $request->file('file');
        
        try {
            $data = Excel::toArray(new StocksImport, $file);
            
            if (empty($data)) {
                return back()->with('error', 'Le fichier est vide ou ne peut pas être lu.');
            }

            $filePath = $file->store('temp');

            return view('preview', [
                'data' => $data[0], // Première feuille
                'filePath' => $filePath,
                'headers' => !empty($data[0][0]) ? array_keys($data[0][0]) : []
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la lecture du fichier: '.$e->getMessage());
        }
    }

    // Exécute l'import final
    public function executeImport(Request $request)
    {
        $validated = $request->validate([
            'filePath' => 'required|string',
            'confirmed' => 'required|boolean'
        ]);

        if ($validated['confirmed']) {
            try {
                Excel::import(new StocksImport, storage_path('app/'.$validated['filePath']));
                return redirect()->route('stocks.index')->with('success', 'Importation réussie!');
            } catch (\Exception $e) {
                return redirect()->route('import.form')->with('error', 'Erreur lors de l\'importation: '.$e->getMessage());
            }
        }

        return redirect()->route('import.form')->with('warning', 'Importation annulée');
    }
}
