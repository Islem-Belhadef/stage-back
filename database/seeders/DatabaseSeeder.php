<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Question;
use App\Models\Speciality;
use App\Models\University;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        University::factory()->create([
            'name' => 'University of Constantine 2 Abdelhamid Mehri',
            'abbreviation' => 'UC2',
            'address' => 'Ali Mendjli El-Khroub, Constantine',
        ]);

        Company::factory()->create([
            'name' => 'Ooredoo',
            'email' => 'support@ooredoo.dz',
            'address' => 'Ali Mendjli El-Khroub, Constantine',
            'logo_link' => 'https://seeklogo.com/images/O/ooredoo-logo-DC5A203B79-seeklogo.com.png',
            'description' => 'Algerian telecommunications company',
        ]);

        Faculty::factory()->create([
            'university_id' => 1,
            'name' => "Nouvelles Technologies de l'Information et Communication",
            'abbreviation' => 'NTIC',
        ]);

        Department::factory()->create([
            'faculty_id' => 1,
            'name' => "Informatique Fondamentale et ses Applications",
            'abbreviation' => 'IFA',
        ]);

        Speciality::factory()->create([
            'department_id' => 1,
            'name' => "Technologies de l'Information",
            'abbreviation' => 'TI',
        ]);

        Question::factory()->create([
            'content' => 'How long does it take to treat a demand or application?',
            'answer' => 'Usually for an internship demand it takes about 1-2 weeks, and for an offer application it takes less than a week',
            'frequent' => 1
        ]);
        Question::factory()->create([
            'content' => 'How will my application be treated?',
            'answer' => 'First it will go to the head of your department to be reviewed, when accepted it gets transferred to the internship supervisor to be reviewed again',
            'frequent' => 1
        ]);
        Question::factory()->create([
            'content' => 'Can I apply for more than one internship at a time?',
            'answer' => 'You can apply for more than one at a time, but if you get accepted you can only do one internship',
            'frequent' => 1
        ]);
        Question::factory()->create([
            'content' => 'What is the difference between offer application and internship demand?',
            'answer' => "Both of them are ways to apply to an internship, but an application is applying to an already existing offer in the platform while a demand is when the internship you're looking for does not exist, so you make a manual demand for this internship",
            'frequent' => 1
        ]);
    }
}
