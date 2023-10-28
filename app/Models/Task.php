<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'completed_at', 'spent_time', 'estimated_time', 'assigned_user_id'];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function shortDescription(): ?string
    {
        if (null === $this->description) {
            return null;
        }
        return Str::limit($this->description, 100, '…');
    }

}
