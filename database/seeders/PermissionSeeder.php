<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.update',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.update',
            'permissions.delete',

            // Students
            'students.view',
            'students.create',
            'students.edit',
            'students.update',
            'students.delete',
            'students.status',

            // Course Types
            'coursetypes.view',
            'coursetypes.create',
            'coursetypes.edit',
            'coursetypes.update',
            'coursetypes.delete',
            'coursetypes.status',

            // Courses
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.update',
            'courses.delete',
            'courses.status',
            'courses.banner_status',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.update',
            'categories.delete',
            'categories.status',

            // Payments
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.update',
            'payments.delete',
            'payments.status',
            'payments.generate_receipt',

            // Student Courses
            'studentcourses.view',
            'studentcourses.create',
            'studentcourses.edit',
            'studentcourses.update',
            'studentcourses.delete',
            'studentcourses.status',

            // Subjects
            'subjects.view',
            'subjects.create',
            'subjects.edit',
            'subjects.update',
            'subjects.delete',
            'subjects.status',

            // News Updates
            'news.view',
            'news.create',
            'news.edit',
            'news.update',
            'news.delete',
            'news.status',

            // Sliders
            'sliders.view',
            'sliders.create',
            'sliders.edit',
            'sliders.update',
            'sliders.delete',
            'sliders.status',

            

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
