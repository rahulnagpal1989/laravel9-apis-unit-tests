<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dummyArr = [
            [
            //   "id"=> 1,
              "name"=> "Arisha Barron"
            ],
            [
            //   "id"=> 2,
              "name"=> "Branden Gibson"
            ],
            [
            //   "id"=> 3,
              "name"=> "Rhonda Church"
            ],
            [
            //   "id"=> 4,
              "name"=> "Georgina Hazel"
            ]
        ];
        foreach($dummyArr as $arr) {
            $user = \App\Models\User::factory()->create($arr);
            $account = \App\Models\Account::create(["user_id"=> $user->id, "balance" => rand(100, 1000), "is_active" => 1]);
        }
    }
}
