<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = [
        'user_id',
        'assigned_to',
        'title',
        'description',
        'image',
        'status',
        'priority',
        'sector',
        'faculty',
    ];
    protected function casts()
{
    return [
        'image' => 'array'
    ];
}

  protected static function booted(): void
{
    static::deleting(function ($ticket) {
        if ($ticket->image) {
            Storage::disk('local')->delete($ticket->image);
        }
    });

    static::updating(function ($ticket) {
        if ($ticket->isDirty('image')) {
            $original = $ticket->getOriginal('image') ?? [];
            $new = $ticket->image ?? [];

            $removed = array_diff($original, $new);

            if (! empty($removed)) {
                Storage::disk('local')->delete($removed);
            }
        }
    });
}



    public function user()
{
    return $this->belongsTo(User::class);
}

public function assignedSupport()
{
    return $this->belongsTo(User::class, 'assigned_to');
}

public function comments()
{
    return $this->hasMany(TicketComment::class);
}

}
