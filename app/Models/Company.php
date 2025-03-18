<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $guarded = [];

    public function primaryContact()
    {
        return $this->belongsTo(User::class, 'primary_contact_id');
    }

    public function businessUnits()
    {
        return $this->hasMany(BusinessUnit::class);
    }

    public function departments()
    {
        return $this->hasManyThrough(Department::class, BusinessUnit::class);
    }

    public function teams()
    {
        return $this->hasManyThrough(Team::class, BusinessUnit::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, BusinessUnit::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }

    public function packages()
    {
        return $this->belongsTo(Package::class);
    }
}
