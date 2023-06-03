<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Faculty;
use App\Models\HeadOfDepartment;
use App\Models\Internship;
use App\Models\Question;
use App\Models\Speciality;
use App\Models\Student;
use App\Models\SuperAdministrator;
use App\Models\Supervisor;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rules\In;

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
            'company_email' => 'support@ooredoo.dz',
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
        User::factory()->create([
            'email' => 'islem.belhadef@univ-constantine2.dz',
            'password' => bcrypt("123456"),
            'first_name' => "Mohamed Islem",
            'last_name' => "Belhadef",
            'email_verified_at' => date("Y-m-d h:i:sa"),
            'role' => 0
        ]);
        User::factory()->create([
            'email' => 'wissem.bouarroudj@ooredoo.dz',
            'password' => bcrypt("123456"),
            'first_name' => "Wissem",
            'last_name' => "Bouarroudj",
            'email_verified_at' => date("Y-m-d h:i:sa"),
            'role' => 2
        ]);
        User::factory()->create([
            'email' => 'nabil.bellala@univ-constantine2.dz',
            'password' => bcrypt("123456"),
            'first_name' => "Nabil",
            'last_name' => "Bellala",
            'email_verified_at' => date("Y-m-d h:i:sa"),
            'role' => 1
        ]);
        User::factory()->create([
            'email' => 'akram.seghiri@univ-constantine2.dz',
            'password' => bcrypt("123456"),
            'first_name' => "Akram",
            'last_name' => "Seghiri",
            'email_verified_at' => date("Y-m-d h:i:sa"),
            'role' => 3
        ]);
        Student::factory()->create([
            'user_id' => 1,
            'department_id' => 1,
            'speciality_id' => 1,
            'academic_year' => "2022/2023",
            'semester' => 6,
            'level' => "L3",
            'date_of_birth' => date("2002-04-01"),
            'place_of_birth' => "Constantine"
        ]);
        Supervisor::factory()->create([
            'user_id' => 2,
            'company_id' => 1,
        ]);
        HeadOfDepartment::factory()->create([
            'user_id' => 3,
            'department_id' => 1,
        ]);
        SuperAdministrator::factory()->create([
            'user_id' => 4,
        ]);
        Internship::factory()->create([
            'student_id' => 1,
            'supervisor_id' => 1,
            'title' => "Internship title",
            'start_date' => date("2023-04-01"),
            'end_date' => date("2002-07-01"),
            'duration' => 90
        ]);
        Evaluation::factory()->create([
            'internship_id' => 1,
            'discipline' => 3.5,
            'aptitude' => 3.0,
            'initiative' => 3.2,
            'innovation' => 2.8,
            'acquired_knowledge' => 3.0,
            'global_appreciation' => "Un très bon stagiaire, toujours present."
        ]);
        Certificate::factory()->create([
            'internship_id' => 1
        ]);
    }
}
