<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palette extends Model
{
    use HasFactory;




    public function emplacement()
{
    return $this->belongsTo(Emplacement::class);
}
}
