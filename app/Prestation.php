<?php

namespace App;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;

use Eloquent as Model;



class Prestation extends Model
{
    //use HasFactory;
    protected $fillable = [
        'code',
        'label',
        'created_by',
        'custom_fields'
        ];


    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code'=> 'string',
        'label' => 'string',
        'created_by' => 'integer',
        'custom_fields' => 'array'
    ];

     /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:prestations,label',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function logs()
    {
        return $this->hasMany(PrestationLog::class);
    }
}
