<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScorecardApproval extends Model
{
    /** @use HasFactory<\Database\Factories\ScorecardApprovalFactory> */
    use HasFactory;

    protected $guarded = [];

    public function scorecardAppraisal()
    {
        return $this->belongsTo(ScorecardAppraisal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
