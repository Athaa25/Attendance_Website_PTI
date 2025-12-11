<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActorNameAttribute(): string
    {
        return $this->user?->name ?? 'Sistem';
    }

    public function getEntityLabelAttribute(): string
    {
        $entity = $this->entity_type ? class_basename($this->entity_type) : null;
        $id = $this->entity_id;

        if (! $entity && ! $id) {
            return '-';
        }

        return trim("{$entity} #{$id}");
    }
}
