<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrestationLog extends Model
{
    use HasFactory;
    protected $fillable = ['prestation_id', 'date', 'quantite', 'user_id'];
    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }

    public static function getDynamicPivotData()
    {
        // 1. Récupérer tous les codes existants
        $codes = DB::table('prestations')->pluck('code');

        // 2. Construire dynamiquement les colonnes SUM(CASE ...)
        $selects = ["prestation_logs.date"];
        foreach ($codes as $code) {
            $selects[] = "SUM(CASE WHEN prestations.code = '$code' THEN prestation_logs.quantite ELSE 0 END) AS `$code`";
        }

        // 3. Créer la requête avec les colonnes dynamiques
        return DB::table('prestation_logs')
            ->join('prestations', 'prestations.id', '=', 'prestation_logs.prestation_id')
            ->selectRaw(implode(', ', $selects))
            ->groupBy('prestation_logs.date')
            ->orderBy('prestation_logs.date');
    }

    public static function prestationCodes()
    {
        return DB::table('prestations')->pluck('code')->toArray();
    }
    
}
