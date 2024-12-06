<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
		Schema::create('timelines', static function (Blueprint $table) {
			$table->id();
			$table->string('name');
            $table->string('type');

            $table->morphs('owner');

			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('timelines');
	}
};