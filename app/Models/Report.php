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


    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function getPriority($type)
    {
        $priorities = [
            'Emergency' => 5,
            'Kebahayaan (Lokasi, Lingkungan, Atau Kerusakan)' => 4,
            'Ketidaksesuaian Prosedur' => 3,
            'Komplain dan Pengaduan' => 2,
            'Perbaikan dan Pemeliharaan' => 1,
        ];

        return $priorities[$type] ?? 1;
    }

    protected $fillable = [
        'title',
        'type',
        'priority',
        'description',
        'photo',
        'status',
        'admin_id',
    ];
}
