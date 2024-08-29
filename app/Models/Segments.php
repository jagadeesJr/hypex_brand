<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segments extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'segments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'title',
        'status',
    ];

    /**
     * Get the business that owns the segment.
     */
    public function business()
    {
        return $this->belongsTo(BrandBusiness::class, 'business_id');
    }
}
