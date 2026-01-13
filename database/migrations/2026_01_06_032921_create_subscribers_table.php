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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            // email
            $table->string('email')->unique();

            // aktywność subskrypcji
            $table->boolean('is_active')->default(true)->index();

            // token do wypisu (RODO / 1-click unsubscribe)
            $table->string('unsubscribe_token', 64)->unique();

            // timestamps biznesowe
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();

            // źródło zapisu: shop | blog | admin | api
            $table->string('source')->nullable()->index();

            // powiązanie z userem (opcjonalne)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // systemowe
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
