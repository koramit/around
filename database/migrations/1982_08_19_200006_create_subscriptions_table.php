<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('subscribable');
            $table->timestamps();
        });

        Schema::create('subscription_user', function (Blueprint $table) {
            $table->primary(['subscription_id', 'subscriber_id']);
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->unsignedInteger('subscriber_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
