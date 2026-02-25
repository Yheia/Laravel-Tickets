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
    ];


    protected static function booted(): void
{
    static::deleting(function ($ticket) {
        if ($ticket->image) {
                    Storage::disk('local')->delete($ticket->image);

        }
    });
    static::updating(function ($ticket) {
    
    if ($ticket->isDirty('image') && $ticket->getOriginal('image')) {
        
        Storage::disk('local')->delete($ticket->getOriginal('image'));
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
}
