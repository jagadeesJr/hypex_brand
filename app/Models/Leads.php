<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'name',
        'phone_number',
        'location',
        'age',
        'email',
        'type',
    ];
    /**
     * Get the business that owns the contact.
     */
    public function business()
    {
        return $this->belongsTo(BrandBusiness::class, 'business_id');
    }
}
