<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Employees extends Component
{
    public $companyId;
    public $users;

    public function mount($companyId)
    {
        $this->companyId = $companyId;
        $this->loadEmployees();
    }

    public function loadEmployees()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Check if the logged-in user has special rights or is an admin
        if ($user->special_rights === 'full_rights') {
            // Super Admin or CEO: Show all employees in the company
            $this->users = User::where('company_id', $this->companyId)->get();
        } elseif ($user->special_rights === 'admin_rights') {
            // Company Admin: Show employees based on the department
            $this->users = User::where('company_id', $this->companyId)
                               ->where('department_id', $user->department_id)
                               ->get();
        } elseif ($user->employee_level === 'department_manager') {
            // Department Manager: Only show their subordinates
            $this->users = User::where('company_id', $this->companyId)
                               ->where('department_id', $user->department_id)
                               ->where('supervisor_id', $user->id)
                               ->get();
        } elseif ($user->employee_level === 'business_unit_manager') {
            // Business Unit Manager: Show employees in their business unit
            $this->users = User::where('company_id', $this->companyId)
                               ->where('business_unit_id', $user->business_unit_id)
                               ->get();
        } else {
            // Regular employee: Show only the logged-in user (self view)
            $this->users = User::where('id', $user->id)->get();
        }
    }
    public function render()
    {
        return view('livewire.employees');
    }
}
