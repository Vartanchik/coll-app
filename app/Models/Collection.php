<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasFactory;

    /**
     * The attributes that removes timestamps columns in DB.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'link'
    ];

    /**
     * Setting that one collection has many contributors.
     *
     * @return HasMany
     */
    public function contributors(): HasMany
    {
        return $this->hasMany(Contributor::class);
    }
}
