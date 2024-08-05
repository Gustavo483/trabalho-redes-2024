<?php

use App\Models\Group;
use App\Models\Message;
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
        Schema::create((new Message)->getTable(), function (Blueprint $table) {
            $table->id('pk_message');
            $table->longText('st_message');
            $table->string('url_file_audio')->nullable();
            $table->string('st_file_type')->nullable();
            $table->string('st_name_file')->nullable();
            $table->unsignedBigInteger('fk_group');
            $table->unsignedBigInteger('fk_user_send_message');
            $table->timestamps();

            $table->foreign('fk_group')->on((new Group)->getTable())->references('pk_group');
            $table->foreign('fk_user_send_message')->on((new User)->getTable())->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Message)->getTable());
    }
};
