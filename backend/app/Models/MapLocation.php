<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'top', 'left', 'institutions'
    ];

    protected $casts = [
        'institutions' => 'array',
    ];

    protected $appends = ['totalAlumni'];

    public function getTotalAlumniAttribute()
    {
        $total = 0;
        foreach ($this->institutions ?? [] as $inst) {
            $total += (int)($inst['alumni'] ?? 0);
        }
        return $total;
    }
}