<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('audit-trail.migration.table'), function (Blueprint $table) {
            $table->id();
            $table->enum('type', config('audit-trail.migration.type'))->default(config('audit-trail.migration.type.created'))->comment(implode(", ", config('audit-trail.migration.type')));    
            $table->string('message', 255)->nullable();
            $table->nullableMorphs('model');
            $table->nullableMorphs('creator');
            $table->json('data')->nullable();
            $table->string('agent')->nullable();
            $table->string('batch_id')->nullable();
            $table->enum('status', config('audit-trail.migration.status'))->default(config('audit-trail.status.unseen'))->nullable();
            $table->timestamps();
        });
    }

    // Drop the audit trail table if rolling back the migration
    public function down()
    {
        Schema::dropIfExists(config('audit-trail.migration.table'));
    }
};
