<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityT1 extends Model
{
    protected $table = 'quality_t1';
    use HasFactory;
    protected $fillable = [
        'position',
        'libel',
    ];

    /**
     * Get the qualities for the quality type.
     */
    public function qualityT2s()
{
    return $this->hasMany(QualityT2::class, 'quality_t1_id')->orderBy('position');
}
}
