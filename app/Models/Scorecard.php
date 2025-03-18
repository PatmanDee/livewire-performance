<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    /** @use HasFactory<\Database\Factories\ScorecardFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scorecardModel()
    {
        return $this->belongsTo(ScorecardModel::class);
    }

    public function scorecardAppraisals()
    {
        return $this->hasMany(ScorecardAppraisal::class);
    }

    public function scorecardApprovals()
    {
        return $this->hasMany(ScorecardApproval::class);
    }

    public function performanceRating()
    {
        return $this->belongsTo(PerformanceRating::class);
    }



}
