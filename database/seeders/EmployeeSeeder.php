<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\BusinessUnit;
use App\Models\AccountType;
use App\Models\Team;
use App\Models\ScorecardModel;
use App\Models\Package;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key constraints
        Schema::disableForeignKeyConstraints();

        try {
            // Step 1: Create package
            $package = Package::create([
                'name' => 'Enterprise',
                'price' => 999.99,
                'billing_cycle' => 'monthly',
                'features' => json_encode(['All Features', 'Unlimited Users', 'Priority Support']),
                'is_active' => true
            ]);

            // Step 2: Create a super admin user
            $superAdmin = User::create([
                'first_name' => 'James',
                'last_name' => 'McGill',
                'email' => 'james@hhm.com',
                'password' => Hash::make('superadmin123'),
                'is_super_admin' => true,
                'special_rights' => 'full_rights',
                'status' => 'active',
                // Add dummy values for required fields
                'company_id' => 0,
                'department_id' => 0,
                'business_unit_id' => 0
            ]);

            // Step 3: Create a company with the super admin as owner
            $company = Company::create([
                'name' => 'Acme Corporation',
                'email' => 'info@acmecorp.com',
                'phone' => '+1-800-555-1234',
                'address' => '123 Business St, Tech City, CA 94043',
                'logo' => 'logos/acme.png',
                'status' => 'active',
                'industry' => 'Technology',
                'website' => 'https://www.acmecorp.com',
                'owner_id' => $superAdmin->id,
                'package_id' => $package->id
            ]);

            // Step 4: Update the super admin with the real company ID
            $superAdmin->update([
                'company_id' => $company->id
            ]);

            // Step 5: Create scorecard model
            $scorecardModel = ScorecardModel::create([
                'name' => 'Standard Performance Model',
                'company_id' => $company->id,
                'description' => 'Standard performance evaluation model',
                'is_active' => true,
                'uses_perspectives' => true,
                'uses_dual_targets' => true
            ]);

            // Step 6: Create account types with hierarchy levels
            $accountTypes = [
                ['name' => 'CEO', 'level' => 1, 'group_name' => 'Executive'],
                ['name' => 'Business Unit Manager', 'level' => 2, 'group_name' => 'Management'],
                ['name' => 'Department Manager', 'level' => 3, 'group_name' => 'Management'],
                ['name' => 'Supervisor', 'level' => 4, 'group_name' => 'Mid-Level'],
                ['name' => 'Employee', 'level' => 5, 'group_name' => 'Staff']
            ];

            $createdAccountTypes = [];
            foreach ($accountTypes as $type) {
                $createdAccountTypes[$type['name']] = AccountType::create([
                    'name' => $type['name'],
                    'level' => $type['level'],
                    'group_name' => $type['group_name'],
                    'company_id' => $company->id,
                    'scorecard_model_id' => $scorecardModel->id
                ]);
            }

            // Step 7: Update super admin with account type
            $superAdmin->update([
                'account_type_id' => $createdAccountTypes['CEO']->id,
            ]);

            // Step 8: Create CEO (owner of the company, separate from super admin)
            $ceo = User::create([
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'ceo@acmecorp.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'is_super_admin' => false,
                'special_rights' => 'full_rights',
                'company_id' => $company->id,
                'account_type_id' => $createdAccountTypes['CEO']->id,
                'scorecard_model_id' => $scorecardModel->id,
                // Temporary values - will update after creating departments and business units
                'department_id' => 0,
                'business_unit_id' => 0,
            ]);

            // Step 9: Create business units (CEO as temporary manager)
            $businessUnits = [
                ['name' => 'Sales & Marketing', 'status' => 'active'],
                ['name' => 'Operations', 'status' => 'active'],
                ['name' => 'Research & Development', 'status' => 'active']
            ];

            $createdBusinessUnits = [];
            foreach ($businessUnits as $bu) {
                $createdBU = BusinessUnit::create([
                    'name' => $bu['name'],
                    'manager_id' => $ceo->id,
                    'company_id' => $company->id,
                    'status' => $bu['status']
                ]);

                $createdBusinessUnits[$bu['name']] = $createdBU;
            }

            // Step 10: Create departments (use CEO as temporary manager)
            $departments = [
                ['name' => 'Sales', 'business_unit' => 'Sales & Marketing'],
                ['name' => 'Marketing', 'business_unit' => 'Sales & Marketing'],
                ['name' => 'Human Resources', 'business_unit' => 'Operations'],
                ['name' => 'Finance', 'business_unit' => 'Operations'],
                ['name' => 'IT', 'business_unit' => 'Operations'],
                ['name' => 'Product Development', 'business_unit' => 'Research & Development'],
                ['name' => 'Quality Assurance', 'business_unit' => 'Research & Development']
            ];

            $createdDepartments = [];
            foreach ($departments as $dept) {
                $createdDept = Department::create([
                    'name' => $dept['name'],
                    'business_unit_id' => $createdBusinessUnits[$dept['business_unit']]->id,
                    'manager_id' => $ceo->id,
                    'status' => 'active'
                ]);

                $createdDepartments[$dept['name']] = $createdDept;
            }

            // Step 11: Update CEO with correct department and business unit
            $ceo->update([
                'department_id' => $createdDepartments['Human Resources']->id,
                'business_unit_id' => $createdBusinessUnits['Operations']->id
            ]);

            // Step 12: Update superadmin with correct department and business unit
            $superAdmin->update([
                'department_id' => $createdDepartments['IT']->id,
                'business_unit_id' => $createdBusinessUnits['Operations']->id
            ]);

            // Step 13: Create business unit managers
            $buManagers = [
                [
                    'first_name' => 'Michael',
                    'last_name' => 'Johnson',
                    'email' => 'michael.johnson@acmecorp.com',
                    'business_unit' => 'Sales & Marketing'
                ],
                [
                    'first_name' => 'Sarah',
                    'last_name' => 'Williams',
                    'email' => 'sarah.williams@acmecorp.com',
                    'business_unit' => 'Operations'
                ],
                [
                    'first_name' => 'David',
                    'last_name' => 'Brown',
                    'email' => 'david.brown@acmecorp.com',
                    'business_unit' => 'Research & Development'
                ]
            ];

            $createdBUManagers = [];
            foreach ($buManagers as $manager) {
                $bu = $createdBusinessUnits[$manager['business_unit']];

                $buManager = User::create([
                    'first_name' => $manager['first_name'],
                    'last_name' => $manager['last_name'],
                    'email' => $manager['email'],
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                    'is_super_admin' => false,
                    'special_rights' => 'admin_rights',
                    'company_id' => $company->id,
                    'supervisor_id' => $ceo->id,
                    'business_unit_id' => $bu->id,
                    'department_id' => $createdDepartments['Human Resources']->id,
                    'account_type_id' => $createdAccountTypes['Business Unit Manager']->id,
                    'scorecard_model_id' => $scorecardModel->id
                ]);

                $createdBUManagers[$manager['business_unit']] = $buManager;

                // Update business unit with its actual manager
                $bu->update(['manager_id' => $buManager->id]);
            }

            // Step 14: Create department managers
            $deptManagers = [];
            foreach ($departments as $index => $dept) {
                $department = $createdDepartments[$dept['name']];
                $bu = $createdBusinessUnits[$dept['business_unit']];
                $buManager = $createdBUManagers[$dept['business_unit']];

                $firstName = ['Robert', 'Lisa', 'James', 'Jennifer', 'William', 'Patricia', 'Richard'][$index % 7];
                $lastName = ['Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Taylor'][$index % 7];

                $deptManager = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => 'manager.' . strtolower(str_replace(' ', '', $dept['name'])) . '@acmecorp.com',
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                    'is_super_admin' => false,
                    'special_rights' => 'admin_rights',
                    'company_id' => $company->id,
                    'supervisor_id' => $buManager->id,
                    'business_unit_id' => $bu->id,
                    'department_id' => $department->id,
                    'account_type_id' => $createdAccountTypes['Department Manager']->id,
                    'scorecard_model_id' => $scorecardModel->id
                ]);

                $deptManagers[$dept['name']] = $deptManager;

                // Update department with its actual manager
                $department->update(['manager_id' => $deptManager->id]);
            }

            // Step 15: Create teams
            $teams = [];
            foreach ($departments as $dept) {
                $department = $createdDepartments[$dept['name']];
                $deptManager = $deptManagers[$dept['name']];

                // Create 1-2 teams per department
                $numTeams = rand(1, 2);
                for ($i = 1; $i <= $numTeams; $i++) {
                    $teamName = $dept['name'] . ' Team ' . $i;

                    $team = Team::create([
                        'name' => $teamName,
                        'business_unit_id' => $department->business_unit_id,
                        'leader_id' => $deptManager->id,
                        'status' => 'active'
                    ]);

                    $teams[$teamName] = $team;
                }
            }

            // Step 16: Create supervisors for each team
            $supervisors = [];
            foreach ($teams as $teamName => $team) {
                $deptName = explode(' Team ', $teamName)[0];
                $department = $createdDepartments[$deptName];
                $deptManager = $deptManagers[$deptName];

                $buName = '';
                foreach ($departments as $dept) {
                    if ($dept['name'] === $deptName) {
                        $buName = $dept['business_unit'];
                        break;
                    }
                }

                $bu = $createdBusinessUnits[$buName];

                $firstName = ['Charles', 'Nancy', 'Steven', 'Mary', 'Joseph', 'Susan'][rand(0, 5)];
                $lastName = ['Clark', 'Lewis', 'Lee', 'Walker', 'Hall', 'Young'][rand(0, 5)];

                $supervisor = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => 'supervisor.' . strtolower(str_replace(' ', '', $teamName)) . '@acmecorp.com',
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                    'is_super_admin' => false,
                    'special_rights' => 'admin_rights',
                    'company_id' => $company->id,
                    'supervisor_id' => $deptManager->id,
                    'business_unit_id' => $bu->id,
                    'department_id' => $department->id,
                    'team_id' => $team->id,
                    'account_type_id' => $createdAccountTypes['Supervisor']->id,
                    'scorecard_model_id' => $scorecardModel->id
                ]);

                $supervisors[$teamName] = $supervisor;

                // Update team with its actual leader (supervisor)
                $team->update(['leader_id' => $supervisor->id]);
            }

            // Step 17: Create regular employees under each supervisor
            foreach ($teams as $teamName => $team) {
                $deptName = explode(' Team ', $teamName)[0];
                $department = $createdDepartments[$deptName];
                $supervisor = $supervisors[$teamName];

                $buName = '';
                foreach ($departments as $dept) {
                    if ($dept['name'] === $deptName) {
                        $buName = $dept['business_unit'];
                        break;
                    }
                }

                $bu = $createdBusinessUnits[$buName];

                // Create 2-3 employees per team
                $numEmployees = rand(2, 3);
                for ($i = 1; $i <= $numEmployees; $i++) {
                    $firstNames = ['Daniel', 'Michelle', 'Christopher', 'Amanda', 'Matthew', 'Stephanie',
                                'Andrew', 'Melissa', 'Joshua', 'Laura', 'Brian', 'Rebecca'];
                    $lastNames = ['Harris', 'Martin', 'Jackson', 'Thompson', 'White', 'Lopez',
                                'Nelson', 'Baker', 'Carter', 'Perez', 'Roberts', 'Turner'];

                    $firstName = $firstNames[array_rand($firstNames)];
                    $lastName = $lastNames[array_rand($lastNames)];

                    User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => 'employee.' . strtolower(str_replace(' ', '', $teamName)) . '.' . $i . '@acmecorp.com',
                        'password' => Hash::make('password123'),
                        'status' => ['active', 'active', 'active', 'on_leave'][rand(0, 3)],
                        'is_super_admin' => false,
                        'special_rights' => 'none',
                        'company_id' => $company->id,
                        'supervisor_id' => $supervisor->id,
                        'business_unit_id' => $bu->id,
                        'department_id' => $department->id,
                        'team_id' => $team->id,
                        'account_type_id' => $createdAccountTypes['Employee']->id,
                        'scorecard_model_id' => $scorecardModel->id
                    ]);
                }
            }
        } finally {
            // Re-enable foreign key constraints
            Schema::enableForeignKeyConstraints();
        }
    }
}