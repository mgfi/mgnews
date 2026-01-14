<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_opens', function (Blueprint $table) {
            $table->id();

            // który newsletter
            $table->foreignId('newsletter_issue_id')
                ->constrained('newsletter_issues')
                ->cascadeOnDelete();

            // który subskrybent (nullable – RODO / anon opens)
            $table->foreignId('subscriber_id')
                ->nullable()
                ->constrained('subscribers')
                ->nullOnDelete();

            // kiedy otwarto (realny event)
            $table->timestamp('opened_at')->index();

            // opcjonalnie: debug / device info
            $table->text('user_agent')->nullable();

            // systemowe
            $table->timestamps();

            // 🔑 indeks pod statystyki
            $table->index(['newsletter_issue_id', 'subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_opens');
    }
};
