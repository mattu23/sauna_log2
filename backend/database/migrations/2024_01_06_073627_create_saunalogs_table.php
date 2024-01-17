<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saunalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('area', 255);
            $table->integer('rank');
            $table->string('comment', 100);
            $table->unsignedBigInteger('userId'); //userIdのカラム追加
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade'); //外部キー制約の追加
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('saunalogs', function (Blueprint $table) {
            $table->dropForeign(['userId']); // 外部キー制約の削除
            $table->dropColumn('userId'); // userIdカラムの削除
        });

        Schema::dropIfExists('saunalogs');
    }
};
