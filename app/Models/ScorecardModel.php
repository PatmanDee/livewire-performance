<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScorecardModel extends Model
{
    /** @use HasFactory<\Database\Factories\ScorecardModelFactory> */
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function perspectives()
    {
        return $this->hasMany(Perspective::class);
    }

    public function accountTypes()
    {
        return $this->hasMany(AccountType::class);
    }

    public function performanceRatings()
    {
        return $this->hasMany(PerformanceRating::class);
    }

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }

}
