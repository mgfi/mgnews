<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_clicks', function (Blueprint $table) {
            $table->id();

            // który newsletter
            $table->foreignId('newsletter_issue_id')
                ->constrained('newsletter_issues')
                ->cascadeOnDelete();

            // który subskrybent (nullable – RODO)
            $table->foreignId('subscriber_id')
                ->nullable()
                ->constrained('subscribers')
                ->nullOnDelete();

            // target
            $table->string('target_type')->nullable(); // url / post / product
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('target_url');

            // hash linku (publiczny)
            $table->string('hash', 64)->unique();

            // kiedy kliknięto
            $table->timestamp('clicked_at')->index();

            // debug / device
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // 🔑 indeksy pod statystyki
            $table->index(['newsletter_issue_id', 'subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_clicks');
    }
};
