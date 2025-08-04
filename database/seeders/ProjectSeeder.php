<?php

namespace Database\Seeders;

use App\Models\AdminProject;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       $project= Project::create([
            'name' => 'RIVER Project',
            'code' => 'RIVER',
            'short_name' => 'RIVER',
            'description' => 'This is a sample project for demonstration purposes.',
            'approval_date' => now(),
            'planned_start_date' => now(),
            'planned_end_date' => now()->addDays(1000),
            'actual_start_date' => now(),
            'budget' => 1000000,
            'funded_by' => 'Government',
            'pd_name' => 'John Doe',
            'pd_contact_no' => '+1234567890',
            'pd_email' => 'john.doe@example.com'
        ]);
        AdminProject::create([
            'admin_id' => '0d81b5a8-2faf-4803-a234-86e7f076dd43', // Assuming admin with ID 1 exists
            'project_id' => $project->id,
        ]);
    }
}
