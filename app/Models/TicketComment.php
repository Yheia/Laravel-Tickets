<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'comment'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted(): void
{
    static::updating(function (TicketComment $comment) {
        if ($comment->isDirty('comment')) {
            $comment->edited_at = now();
        }
    });
}
}