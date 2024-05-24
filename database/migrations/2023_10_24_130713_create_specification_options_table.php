<?php

use App\Models\Specification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Specification::class);
            $table->string('option')->nullable();
            $table->unsignedBigInteger('parent_option_id')->nullable();
            $table->timestamps();

            $table->index(['specification_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specification_options');
    }
};
