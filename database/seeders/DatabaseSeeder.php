<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\University::factory()->create([
             'name' => 'University of Constantine 2 Abdelhamid Mehri',
             'abbreviation' => 'UC2',
             'address' => 'Ali Mendjli El-Khroub, Constantine',
         ]);

         \App\Models\Company::factory()->create([
             'name' => 'Ooredoo',
             'email' => 'support@ooredoo.dz',
             'address' => 'Ali Mendjli El-Khroub, Constantine',
             'logo_link' => 'https://seeklogo.com/images/O/ooredoo-logo-DC5A203B79-seeklogo.com.png',
             'description' => 'Algerian telecommunications company',
         ]);

         \App\Models\Faculty::factory()->create([
             'university_id' => 1,
             'name' => "Nouvelles Technologies de l'Information et Communication",
             'abbreviation' => 'NTIC',
         ]);

         \App\Models\Department::factory()->create([
             'faculty_id' => 1,
             'name' => "Informatique Fondamentale et ses Applications",
             'abbreviation' => 'IFA',
         ]);

         \App\Models\Speciality::factory()->create([
             'department_id' => 1,
             'name' => "Technologies de l'Information",
             'abbreviation' => 'TI',
         ]);
    }
}
