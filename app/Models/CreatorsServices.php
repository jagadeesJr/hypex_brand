<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatorsServices extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'creators_service';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'name',
        'price',
        'discount',
        'status',
    ];

    /**
     * Get the creator that owns the service.
     */
    public function creator()
    {
        return $this->belongsTo(CreatorsAuth::class, 'creator_id');
    }
}
