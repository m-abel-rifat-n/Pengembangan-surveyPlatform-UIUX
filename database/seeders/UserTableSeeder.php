<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::create([
            'first_name'      => 'Super',
            'surname'         => 'Admin',
            'email'     => 'admin@uxdesignmeter.com',
            'birth_date'     => '2002-05-17',
            'gender'     => 'Male',
            'profession'     => 'Super Admin',
            'educational_background'     => 'Super Admin',
            'password'  => bcrypt('qwert@2026!'),
        ]);

        $user2 = User::create([
            'first_name' => 'Abel',
            'surname' => 'RN',
            'email' => 'abel@uxdesignmeter.com',
            'birth_date' => '2004-05-28',
            'gender' => 'Male',
            'profession' => 'Student',
            'educational_background' => "Bachelor's Degree",
            'password' => bcrypt('skripfor2026!'),
        ]);

        $permissions1 = Permission::all();
        $permissions2 = Permission::whereNotIn('name', [
            'dashboard.index.full', 'articles.index.full', 'sus.index.full', 'tam.index.full', 'ab_test.index.full', 'roles.index', 'roles.edit', 'roles.create', 'roles.delete', 'roles.index.full', 'permissions.index',
            'users.index', 'users.delete', 'users.edit', 'users.create', 'article_reports.index', 'article_reports.show', 'article_reports.update_status', 'article_reports.delete',
            'surveys.index.full'
        ])->get();
        $permissions3 = Permission::whereIn('name', [
            'dashboard.index', 'profile.index', 'profile.edit', 'profile.upload.certificate', 'profile.change.password',
            'sus.index', 'sus.statistics', 'sus.charts', 'sus.responses', 'sus.export',
            'tam.index', 'tam.statistics', 'tam.charts', 'tam.responses', 'tam.export',
            'ab_test.index', 'ab_test.statistics', 'ab_test.charts', 'ab_test.responses', 'ab_test.export',
            'wcag_test.index', 'wcag_test.statistics', 'wcag_test.charts', 'wcag_test.responses', 'wcag_test.export',
            'nasa_tlx.index', 'nasa_tlx.statistics', 'nasa_tlx.charts', 'nasa_tlx.responses', 'nasa_tlx.export',
            'visawi_s.index', 'visawi_s.statistics', 'visawi_s.charts', 'visawi_s.responses', 'visawi_s.export',
            'surveys.index', 'surveys.create', 'surveys.edit', 'surveys.delete'
        ])->get();
        $permissions4 = Permission::whereIn('name', [
            'dashboard.index', 'profile.index', 'profile.edit', 'profile.upload.certificate', 'profile.change.password',
            'sus.index', 'sus.statistics', 'sus.charts', 'sus.responses', 'sus.export',
            'tam.index', 'tam.statistics', 'tam.charts', 'tam.responses', 'tam.export',
            'ab_test.index', 'ab_test.statistics', 'ab_test.charts', 'ab_test.responses', 'ab_test.export',
            'wcag_test.index', 'wcag_test.statistics', 'wcag_test.charts', 'wcag_test.responses', 'wcag_test.export',
            'nasa_tlx.index', 'nasa_tlx.statistics', 'nasa_tlx.charts', 'nasa_tlx.responses', 'nasa_tlx.export',
            'visawi_s.index', 'visawi_s.statistics', 'visawi_s.charts', 'visawi_s.responses', 'visawi_s.export',
            'surveys.index', 'surveys.create', 'surveys.edit', 'surveys.delete'
        ])->get();


        $role1 = Role::find(1);
        $role2 = Role::find(2);
        $role3 = Role::find(3);
        $role4 = Role::find(4);

        $role1->syncPermissions($permissions1);
        $role2->syncPermissions($permissions2);
        $role3->syncPermissions($permissions3);
        $role4->syncPermissions($permissions4);

        $user1->assignRole($role1);
        $user2->assignRole($role2);
    }
}
