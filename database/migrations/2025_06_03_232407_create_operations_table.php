<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Partner::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('customer_name');
            $table->enum('operation_type', array_column(\App\Enums\OperationTypeEnum::cases(), 'value'));
            $table->string('invoice_number')->nullable();
            $table->double('invoice_value')->nullable();
//            $table->double('remaining_of_bill')->nullable();// باقي من الفاتورة
            $table->integer('percentage_of_bill')->nullable();// النسبة من الفاتورة
//            $table->double('amount_due')->nullable();// المبلغ المستحق
//            $table->double('remaining_amount')->nullable();// المبلغ المتبقي
            $table->timestamp('invoice_date')->nullable();
            $table->timestamp('alert_date')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
