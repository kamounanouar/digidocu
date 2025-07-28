<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\Palette;
use App\Models\Emplacement;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ArticlesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            $emplacement = Emplacement::firstOrCreate([
                'code_depot' => $row[0],
                'code_zone' => $row[1],
                'code_rack' => $row[2],
                'code_meuble' => $row[3],
                'code_niveau' => $row[4],
                'etat' => $row[5],
                'type_emplacement' => $row[6],
                'code_stockeur' => $row[7],
                'nom_stockeur' => $row[8],
            ]);

            $article = Article::firstOrCreate([
                'code' => $row[9],
            ], [
                'libelle' => $row[10],
            ]);

            Palette::create([
                'article_id' => $article->id,
                'emplacement_id' => $emplacement->id,
                'quantite_palette' => $row[11],
                'quantite_colis' => $row[12],
                'quantite_uvc' => $row[13],
                'numero' => $row[14],
                'code_famille' => $row[15],
                'libelle_famille' => $row[16],
                'date_limite_vente' => $row[17],
                'code_statut' => $row[18],
                'date_entree' => $row[19],
                'code_support' => $row[20],
            ]);
        }
    }
}
