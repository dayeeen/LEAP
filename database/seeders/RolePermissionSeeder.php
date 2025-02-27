<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Membuat beberapa role
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);
        $studentRole = Role::create([
            'name' => 'student'
        ]);
        $teacherRole = Role::create([
            'name' => 'teacher'
        ]);

        // Membuat default user untuk super admin
        $userOwner = User::create([
            'name' => 'Dian Saputra',
            'occupation' => 'CEO of Samsan Tech',
            'avatar' => 'images/default-avatar.png',
            'email' => 'dayensptr@leap.com',
            'password' => bcrypt('password')
        ]);

        $userOwner->assignRole($ownerRole);
    }
}
