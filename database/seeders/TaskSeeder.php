<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priority = ['low', 'medium', 'high'];
        for ($i = 1; $i < 100; $i++) {
            DB::table('tasks')->insert([
                'title' => 'title #' . $i,
                'description' => 'description #' . $i,
                'priority' => $priority[array_rand($priority, 1)],
                'status' => 'active',
                'expired_at' => now()
            ]);
        }
    }
}
