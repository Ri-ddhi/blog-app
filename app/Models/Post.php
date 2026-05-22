<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'user_id',
        'status',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }


    public function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }

    public function categories(): BelongsToMany{
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function statuses(): MorphMany
    {
        return $this->morphMany(Status::class, 'statusable');
    }

}
