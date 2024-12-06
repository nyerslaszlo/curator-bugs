<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
        Schema::create('model_has_media', static function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->foreignId('media_id')->constrained()->cascadeOnDelete();

            $table->integer('order')->default(0);
            $table->string('type')->nullable();
            $table->boolean('hidden')->default(false);
            $table->timestamps();
        });
	}

	public function down(): void
	{
		Schema::dropIfExists('model_has_media');
	}
};
