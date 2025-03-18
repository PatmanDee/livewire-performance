<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScorecardAppraisal extends Model
{
    /** @use HasFactory<\Database\Factories\ScorecardAppraisalFactory> */
    use HasFactory;

    protected $guarded = [];

    public function scorecard()
    {
        return $this->belongsTo(Scorecard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scorecardApproval()
    {
        return $this->hasOne(ScorecardApproval::class);
    }

}
