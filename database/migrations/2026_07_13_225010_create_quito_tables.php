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
        // 1. sections
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->string('subtitle', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('style_variant', 50)->default('default');
            $table->string('background_color', 7)->nullable();
            $table->string('text_color', 7)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. links
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('button_text', 100)->default('Ir al enlace');
            $table->text('destination_url');
            $table->string('link_type', 50)->default('website'); // whatsapp, form, doc, map, telegram, mail, phone, internal, website
            $table->string('redirect_mode', 20)->default('direct'); // direct, interstitial, automatic
            $table->string('icon', 50)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->string('style_variant', 50)->default('default');
            $table->string('background_color', 7)->nullable();
            $table->string('text_color', 7)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('open_new_tab')->default(true);
            $table->boolean('require_confirmation')->default(false);
            $table->string('confirmation_title', 150)->nullable();
            $table->text('confirmation_message')->nullable();
            $table->string('access_code_hash', 255)->nullable();
            $table->integer('max_clicks')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status_label', 100)->nullable();
            $table->text('alternative_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. link_clicks
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained('links')->onDelete('cascade');
            $table->timestamp('clicked_at');
            $table->string('anonymized_ip', 64);
            $table->string('user_agent_summary', 255)->nullable();
            $table->string('referrer', 255)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->timestamps();
        });

        // 4. announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('content');
            $table->string('type', 20)->default('info'); // info, success, warning, danger
            $table->string('button_text', 100)->nullable();
            $table->string('button_url', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. faqs
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100)->nullable();
            $table->string('question', 255);
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. navigation_items
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->string('url', 255);
            $table->string('location', 20)->default('header'); // header, footer, both
            $table->string('target', 20)->default('_self');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general');
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        // 8. activity_logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 100);
            $table->string('entity_type', 100)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });

        // 9. social_links
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 50);
            $table->string('label', 100);
            $table->string('url', 255);
            $table->string('icon', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 10. media
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('filename', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->string('path', 255);
            $table->bigInteger('size');
            $table->string('alt_text', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('link_clicks');
        Schema::dropIfExists('links');
        Schema::dropIfExists('sections');
    }
};
