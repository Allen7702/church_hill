<?php

use Illuminate\Database\Seeder;
use App\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [

            [
                'jina_kamili'=>'Admin',
                'email'=>'admin@atlais.org',
                'anwani' => 'Dar-es-Salaam',
                'mawasiliano' => '0735564640',
                'ngazi'=>'administrator',
                'cheo' => NULL,
                'jumuiya' => NULL,
                'ruhusa' => 'zote',
                'password'=> bcrypt('open0000'),
                'picha' => 'profile.png',
                'mwanajumuiya_id' => NULL,
            ],

        ];
        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
