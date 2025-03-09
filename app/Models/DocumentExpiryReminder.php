<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentExpiryReminder extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentExpiryReminderFactory> */
    use HasFactory;

    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
