<?php

use App\Constants;
use App\Models\RentToOwnApplication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentToOwnApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_to_own_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->nullableMorphs('approvable');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('nib_no')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('dob')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', Constants::GENDERS )->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('island_of_birth')->nullable();
            $table->string('country_of_citizenship')->nullable();
            $table->enum('status', Constants::APPLICATION_STATUSES)->default(RentToOwnApplication::STATUS_SUBMITTED);
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('rent_to_own_applications');
    }
}
