<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('newsletter_issues', function (Blueprint $table) {
            $table->id();

            /*
             |--------------------------------------------------------------------------
             | SUBJECT / TITLE (VISIBLE IN INBOX)
             |--------------------------------------------------------------------------
             */
            $table->string('title_pl');
            $table->string('title_en')->nullable();

            /*
             |--------------------------------------------------------------------------
             | PREHEADER (TEXT NEXT TO SUBJECT IN INBOX)
             |--------------------------------------------------------------------------
             */
            $table->string('preview_text_pl')->nullable();
            $table->string('preview_text_en')->nullable();

            /*
             |--------------------------------------------------------------------------
             | OPTIONAL SLUGS (ARCHIVE / PUBLIC VIEW / SEO)
             |--------------------------------------------------------------------------
             */
            $table->string('slug_pl')->nullable()->index();
            $table->string('slug_en')->nullable()->index();

            /*
             |--------------------------------------------------------------------------
             | CONTENT
             |--------------------------------------------------------------------------
             */
            $table->json('content_json')->nullable();
            $table->longText('content_html')->nullable();

            /*
             |--------------------------------------------------------------------------
             | DENORMALIZED COUNTER (FOR SORTING & PERFORMANCE)
             |--------------------------------------------------------------------------
             */
            $table->unsignedInteger('blocks_count')->default(0)->index();

            /*
             |--------------------------------------------------------------------------
             | STATUS
             |--------------------------------------------------------------------------
             */
            $table->enum('status', ['draft', 'sending', 'sent'])
                ->default('draft')
                ->index();

            /*
             |--------------------------------------------------------------------------
             | SEND TIMESTAMP
             |--------------------------------------------------------------------------
             */
            $table->timestamp('sent_at')->nullable();

            /*
             |--------------------------------------------------------------------------
             | AUTHOR
             |--------------------------------------------------------------------------
             */
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_issues');
    }
};
