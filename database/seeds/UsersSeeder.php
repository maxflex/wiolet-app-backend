<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->where('id', '>', 1)->delete();
        factory(App\Models\User::class, 50)->create();
    }
}
