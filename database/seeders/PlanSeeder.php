<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $plan1 = new Plan([
        //     'name' => 'IYKE63 Basic',
        //     'amount' => '70.00',
        //     'interval' => 91,
        //     'plan_code' => 'PLN_24vleytjdmzq0uu'
        // ]);
        // $plan1->save();

        // $plan2 = new Plan([
        //     'name' => 'IYKE63 Deluxe',
        //     'amount' => '120.00',
        //     'interval' => 182,
        //     'plan_code' => 'PLN_2qr1pzn7mtid46f'
        // ]);
        // $plan2->save();

        // $plan3 = new Plan([
        //     'name' => 'IYKE63 Gold',
        //     'amount' => '150.00',
        //     'interval' => 360,
        //     'plan_code' => 'PLN_dp7yn40oug7i0ej'
        // ]);
        // $plan3->save();

        // Admin::create([
        //     'firstname' => 'admin',
        //     'lastname' => 'iyke',
        //     'email' => 'admin@iyke63.com',
        //     'password' => Hash::make('12345678')
        // ]);

        $announcement = new Announcement([
            'title' => 'Early User Welcome',
            'body' => 'Welcome to Iyke63 Lotto hub. As an early user, you have the opportunity to use our prediction application for free while testing the various features we have for you. Good luck winning'
        ]);
        $announcement->save();

        $announcement2 = new Announcement([
            'title' => 'Stake Lotto Request disabled Temporarily',
            'body' => 'Our Stake lotto feature has been disabled temporarily as updates are still being made to this feature. Whiles you wait, feel free to explore the other premium services we have to offer'
        ]);
        $announcement2->save();
    }
}
