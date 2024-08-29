<?php

namespace App\Models;

use App\Models\BrandBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappRegistration extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'whatsapp_reg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'status',
    ];

    /**
     * Get the business that owns the WhatsApp registration.
     */
    public function business()
    {
        return $this->belongsTo(BrandBusiness::class, 'business_id');
    }
}
