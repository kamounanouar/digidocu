<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityT2 extends Model
{

    protected $table = 'quality_t2';
    use HasFactory;

    
    protected $fillable = [
        'quality_t1_id',
        'position',
        'libel',
    ];

    /**
     * Get the quality type that owns the quality.
     */
    public function qualityT1()
    {
        return $this->belongsTo(QualityT1::class, 'quality_t1_id');
    }
    
    public function qualityLogs()
    {
        return $this->hasMany(QualityLogT2::class, 'quality_t2_id');
    }
}
