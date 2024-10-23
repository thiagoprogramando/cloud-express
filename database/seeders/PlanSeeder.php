<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder {

    public function run(): void {
        DB::table('plans')->insert([
            'name'          => 'Gratuito',
            'description'   => 'Para você experimentar o nosso software!',
            'space_disk'    => 1,
            'space_user'    => 2,
            'value'         => 0,
            'validate'      => 'lifetime',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
