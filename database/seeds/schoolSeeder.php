<?php

use Illuminate\Database\Seeder;

class schoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Creating school objects*/
        for ($i=0;$i<10;$i++)
        {
            $school_admin=factory(App\Models\User::class)->create(["role"=>"school_admin"]);
            factory(App\Models\School::class)->create(["admin_id"=>$school_admin->id]);
        }
        
        /*Adding related tables to school*/
        for($i=1;$i<11;$i++)
        {
            factory(App\Models\SchoolImage::class)->create(["school_id"=>$i]);
            factory(App\Models\SchoolStage::class)->create(["school_id"=>$i]);
            factory(App\Models\SchoolCertificate::class)->create(["school_id"=>$i]);
            factory(App\Models\SchoolFacility::class,3)->create(["school_id"=>$i]);
        }
    }
}
