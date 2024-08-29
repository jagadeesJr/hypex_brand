<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandServices extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brand_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'name',
        'duration',
        'price',
        'tax',
        'discount',
        'description',
        'status',
    ];

    /**
     * Get the business that owns the service.
     */
    public function business()
    {
        return $this->belongsTo(BrandBusiness::class, 'business_id');
    }
}
