<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('admin')->default(false);
            $table->timestamps();
        });

        /*
        Schema::create('seats', function (Blueprint $table) {
            $table->string('seat_number')->unique()->primary();
            $table->integer('base_price');

            $table->check("seat_number REGEXP '^[A-Za-z][0-9]{3}$'");

        });


         Schema::create('events', function (Blueprint $table) {
            $table->string('title');
            $table->text('description');
            $table->timestamp('event_date_at');
            $table->timestamp('sale_start_at');
            $table->timestamp('sale_end_at');
            $table->boolean('is_dynamic_price');
            $table->integer('max_number_allowed');
            $table->string('image')->nullable();


            $table->check('CHAR_LENGTH(description) <= 1000');

        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->string('barcode');
            $table->timestamp('admission_time');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->integer('seat_id');
            $table->foreign('seat_id')->references('seat_number')->on('seats');
            $table->float('price');

            $table->check("barcode REGEXP '^[0-9]{9}$'");
            $table->unique(['user_id', 'event_id', 'seat_id']);

        });
        */


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
