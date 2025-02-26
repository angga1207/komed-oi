<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Developer',
                'slug' => 'developer'
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin'
            ],
            [
                'name' => 'Verifikator',
                'slug' => 'verifikator'
            ],
            [
                'name' => 'User',
                'slug' => 'user'
            ]
        ];

        foreach ($roles as $role) {
            $checkRoles = DB::table('roles')->where('slug', $role['slug'])->first();
            if (!$checkRoles) {
                DB::table('roles')->insert($role);
            } else {
                DB::table('roles')->where('slug', $role['slug'])->update($role);
            }
        }

        $users = [
            [
                'fullname' => 'Developer',
                'first_name' => 'Developer',
                'last_name' => '',
                'username' => 'developer',
                'email' => 'developer@komed.oganilirkab.go.id',
                'photo' => '/storage/images/users/default.png',
                'email_verified_at' => now(),
                'password' => bcrypt('developer'),
                'status' => 'active',
                'role_id' => 1,
            ],
            [
                'fullname' => 'Admin',
                'first_name' => 'Admin',
                'last_name' => '',
                'username' => 'admin',
                'email' => 'admin@komed.oganilirkab.go.id',
                'photo' => '/storage/images/users/default.png',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'status' => 'active',
                'role_id' => 2,
            ],
            [
                'fullname' => 'Verifikator',
                'first_name' => 'Verifikator',
                'last_name' => '',
                'username' => 'verifikator',
                'email' => 'verifikator@komed.oganilirkab.go.id',
                'photo' => '/storage/images/users/default.png',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'status' => 'active',
                'role_id' => 3,
            ],
            // [
            //     'fullname' => 'User',
            //     'first_name' => 'User',
            //     'last_name' => '',
            //     'username' => 'user',
            //     'email' => 'user@komed.oganilirkab.go.id',
            //     'photo' => '/storage/images/users/default.png',
            //     'email_verified_at' => now(),
            //     'password' => bcrypt('password'),
            //     'status' => 'active',
            //     'role_id' => 4,
            // ]
        ];

        foreach ($users as $user) {
            $checkUsers = DB::table('users')->where('username', $user['username'])->first();
            if (!$checkUsers) {
                DB::table('users')->insert($user);
            } else {
                DB::table('users')->where('username', $user['username'])->update($user);
            }
        }

        Announcement::create([
            'title' => 'Selamat Datang di Aplikasi KOMED',
            'content' => 'Selamat datang di aplikasi KOMED. ',
            'image' => null,
            'link' => null,
            'is_active' => 1,
            'published_at' => now(),
            'created_by' => 1
        ]);
    }
}
