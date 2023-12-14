<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MuscleSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(ExerciseTypeSeeder::class);
        $this->call(CircuitSeeder::class);
        $this->call(CardioSeeder::class);
        $this->call(ExerciseSeeder::class);
        //$this->call(WorkoutSeeder::class); // Deprecated
        $this->call(jsonSeeder::class);
        $this->call(ActivePlanSeeder::class);
    }
}
