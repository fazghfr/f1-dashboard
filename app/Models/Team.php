<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if follows convention)
    protected $table = 'racing_teams';

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'origin',
        'livery_color',
        'team_chief'
    ];

    /**
     * Get the drivers for the racing team.
     */
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
