<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_number',
        'base_price',
    ];

    /**
     * Cast attributes to native types
     *
     * @var array<string,string>
     */
    protected $casts = [
        'base_price' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
