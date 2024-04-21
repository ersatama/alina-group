<?php

namespace App\Models;

use App\Models\Scopes\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'expired_at'
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new Page);
    }
}
