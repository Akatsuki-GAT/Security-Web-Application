<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // This is just an ALTER function of the table which was pre made(the ones that start 0001...) after
   // setting connections to the demo DB and running cmd 'php artisan migrate'
public function up()
{
    Schema::table('users', function (Blueprint $table) {

        // Rename name → full_name
        $table->renameColumn('name', 'full_name');

        // Add username
        $table->string('username')->unique()->after('id');

        // Add contact
        $table->string('contact')->nullable()->after('full_name');

        // Remove unused columns
        $table->dropColumn(['email_verified_at', 'remember_token']);
    });
}

    
    
};
