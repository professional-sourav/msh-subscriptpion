<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            'title'         => "Basic Plan",
            'identifier'    => "basic",
            'stripe_id'     => "price_1Kq4lMCxH9FouK7tfqukya76",
        ]);

        DB::table('plans')->insert([
            'title'         => "Advance Plan",
            'identifier'    => "advance",
            'stripe_id'     => "price_1Kq4lMCxH9FouK7thQY5Zvxu",
        ]);

        DB::table('plans')->insert([
            'title'         => "Freelancer Plan",
            'identifier'    => "freelancer",
            'stripe_id'     => "price_1Kq4lMCxH9FouK7tq5ZOsrmO",
        ]);
    }
}
