<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $report->shareable_token = Str::uuid();
        });
    }

    protected $fillable = [
        'title',
        'type',
        'description',
        'photo',
        'status',
    ];
}
