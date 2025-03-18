<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perspective extends Model
{
    /** @use HasFactory<\Database\Factories\PerspectiveFactory> */
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scorecardModel()
    {
        return $this->belongsTo(ScorecardModel::class);
    }

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }
}
