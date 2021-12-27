<?php

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
        if (!\App\Config::query()->find(1)) {
            \App\Config::query()->create();
        }

        if (!\App\Admin::query()->find(1)) {
            \App\Admin::query()->create([
                'username' => 'admin',
                'password' => hash('sha256', 'admin')
            ]);
        }
    }
}
