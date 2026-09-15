<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up():void{Schema::create('payments',function(Blueprint $t){$t->id();$t->foreignId('booking_id')->constrained()->cascadeOnDelete();$t->string('reference',191)->unique();$t->decimal('amount',10,2);$t->string('method')->default('demo');$t->string('status')->default('success');$t->timestamp('paid_at')->nullable();$t->timestamps();});}public function down():void{Schema::dropIfExists('payments');}};
