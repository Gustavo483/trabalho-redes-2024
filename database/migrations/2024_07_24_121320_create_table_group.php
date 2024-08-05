<?php

use App\Models\Group;
use App\Models\User;
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
        Schema::create((new Group)->getTable(), function (Blueprint $table) {
            $table->id('pk_group');
            $table->string('st_name');
            $table->string('st_description');
            $table->unsignedBigInteger('fk_user_admin');
            $table->boolean('bl_active')->default(true);

            $table->foreign('fk_user_admin')->on((new User)->getTable())->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Group)->getTable());
    }
};
