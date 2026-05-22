<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'statusable', 'changed_by'];
    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }

    public function modernator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

}
