<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Group;
use App\Models\Task;
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
        // \App\Models\User::factory(10)->create();

         \App\Models\User::create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password' => bcrypt('asd12345')
         ]);

        \App\Models\User::create([
            'name' => 'Abdullah',
            'email' => 'abodi.imz@gmail.com',
            'password' => bcrypt('asd12345')
        ]);

         Group::create([
             'name'=>'Group 1',
             'description'=>'Tasks Group 1',
             'user_id'=>'1'
         ]);

         Task::create([
             'title'=>'Test task 1',
             'description'=>'This is a test task 1',
             'group_id'=>'1',
             'user_id'=>'1',
             'start'=>'2023-2-14 01:00',
             'end'=>'2023-2-20 01:00',
         ]);

        Task::create([
            'title'=>'Test task 2',
            'description'=>'This is a test task 2',
            'group_id'=>'1',
            'user_id'=>'1',
            'start'=>'2023-2-14 01:00',
            'end'=>'2023-2-20 01:00',
        ]);

        Task::create([
            'title'=>'Test task 3',
            'description'=>'This is a test task 3',
            'group_id'=>'1',
            'user_id'=>'1',
            'start'=>'2023-2-14 01:00',
            'end'=>'2023-2-20 01:00',
        ]);

    }
}
