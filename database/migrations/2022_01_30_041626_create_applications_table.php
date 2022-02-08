<?php

use App\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('fname');
            $table->string('lname');
            $table->string('nib_no');
            $table->string('email');
            $table->dateTime('dob');
            $table->string('phone');
            $table->enum('gender', Constants::GENDERS );
            $table->string('country_of_birth');
            $table->string('island_of_birth');
            $table->string('country_of_citizenship');
            $table->string('house_no');
            $table->string('street_address');
            $table->string('po_box');
            $table->string('island');
            $table->string('country');
            $table->string('home_phone');
            $table->string('passport_no');
            $table->date('passport_expiry');
            $table->string('driving_licence_no');
            $table->string("nib_photo");
            $table->string("passport_photo");
            $table->string('employer');
            $table->string('industry');
            $table->string('position');
            $table->string('work_phone');
            $table->string('payment_slip');
            $table->string('status')->default("submitted");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
