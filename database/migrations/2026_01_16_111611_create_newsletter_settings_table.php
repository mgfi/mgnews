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

            // język rekordu
            $table->string('locale', 5)->default('pl');

            // domyślny język systemu
            $table->string('default_locale', 2)->default('en');

            // dane firmy
            $table->string('company_name');
            $table->text('company_address')->nullable();
            $table->string('company_email')->nullable();

            // polityka prywatności
            $table->string('privacy_url')->nullable();

            // stopka newslettera
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
