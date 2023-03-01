<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAndCamera extends Model
{
    use HasFactory;

    public function scopeCurrentUser($query)
    {
        $query->where('user_id', userAuthInfo()->id);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'type',
        'user_id',
    ];
}
