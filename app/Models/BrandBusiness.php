<?php

namespace App\Models;

use App\Models\BrandAuthentication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandBusiness extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brand_business';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name',
        'brand_id',
        'description',
        'branch_area',
        'categories',
        'subcategories',
        'target_audience',
        'business_hours',
        'interests',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'branch_area' => 'array',
        'categories' => 'array',
        'subcategories' => 'array',
        'business_hours' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define the relationship with the BrandAuth model.
     */
    public function brand()
    {
        return $this->belongsTo(BrandAuthentication::class, 'brand_id');
    }

}
