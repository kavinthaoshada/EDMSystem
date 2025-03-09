<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function expiryReminder()
    {
        return $this->hasOne(DocumentExpiryReminder::class, 'document_id');
    }
}
