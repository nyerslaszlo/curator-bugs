<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
		Schema::create('timeline_entries', static function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('subtitle')->nullable();
			$table->date('date');

            $table->foreignId('timeline_id')->constrained('timelines')->cascadeOnDelete();

			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('timeline_entries');
	}
};
