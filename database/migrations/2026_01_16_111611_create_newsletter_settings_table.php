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
        Schema::create('newsletter_settings', function (Blueprint $table) {
            $table->id();

            $table->string('locale', 5)->default('pl');

            $table->string('company_name');
            $table->text('company_address')->nullable();
            $table->string('company_email')->nullable();

            $table->string('privacy_url')->nullable();

            $table->text('footer_text')->nullable();

            $table->timestamps();

            $table->unique('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_settings');
    }
};
