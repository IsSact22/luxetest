<?php

namespace Database\Seeders;

use App\Models\ProjectService;
use App\Models\ProjectTask;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectServices = ProjectService::count();
        ProjectTask::factory()->count($projectServices)->create();
    }
}
