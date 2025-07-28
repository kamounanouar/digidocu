<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityLogT2 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quality_log_t2';

    use HasFactory;
    
    protected $fillable = [
        'quality_id',
        'quality_t2_id',
        'status', // true for 'oui', false for 'non'
        'comment',
    ];


    /**
     * Get the quality that owns the quality log.
     */
    public function quality()
    {
        return $this->belongsTo(Quality::class, 'quality_id');
    }
    
    public function qualityT2()
    {
        return $this->belongsTo(QualityT2::class, 'quality_t2_id');
    }
}
