<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if follows convention)
    protected $table = 'drivers';

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'driver_number',
        'origin',
        'age',
        'points',
        'racing_team_id',
        'role'
    ];

    /**
     * Get the racing team that owns the driver.
     */
    public function racingTeam()
    {
        return $this->belongsTo(Team::class);
    }
}
