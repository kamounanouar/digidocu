<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'comment'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    /**
     * Get the quality logs for the quality.
     */
    public function qualityLogs()
    {
        return $this->hasMany(QualityLogT2::class,"quality_id");
    }
}
