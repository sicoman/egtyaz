<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        return false ;
        //Eloquent::unguard();

        $path = base_path('users.sql');
        // DB::unprepared(file_get_contents($path));
        $this->command->info('Users - Roles - premissions tables seeded succefully!');


        $path = base_path('options.sql');
        DB::unprepared(file_get_contents($path));
        $this->command->info('Options table seeded succefully!');
        
        
    }
}
