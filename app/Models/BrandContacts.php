<?php

namespace App\Models;

use App\Models\Groups;
use App\Models\Segments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandContacts extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'group_id',
        'segment_id',
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

    /**
     * Get the group that the contact belongs to.
     */
    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    /**
     * Get the segment that the contact belongs to.
     */
    public function segment()
    {
        return $this->belongsTo(Segments::class, 'segment_id');
    }
}
