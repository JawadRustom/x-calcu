<?php

namespace Database\Factories;

use App\Enums\OperationTypeEnum;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoiceDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $alertDate = Carbon::parse($invoiceDate)->addDays($this->faker->numberBetween(15, 60));
        $invoiceValue = $this->faker->randomFloat(2, 100, 100000);
        $percentage = $this->faker->numberBetween(1, 100);
        $remainingOfBill = $invoiceValue * ($percentage / 100);
        $amountDue = $remainingOfBill * 0.8; // Assuming 20% is paid upfront

        return [
            'partner_id' => Partner::factory(),
            'customer_name' => $this->faker->company(),
            'operation_type' => $this->faker->randomElement(OperationTypeEnum::cases()),
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'invoice_value' => $invoiceValue,
            'remaining_of_bill' => $remainingOfBill,
            'percentage_of_bill' => $percentage,
            'amount_due' => $amountDue,
            'remaining_amount' => $amountDue,
            'invoice_date' => $invoiceDate,
            'alert_date' => $alertDate,
            'comments' => $this->faker->optional(0.7)->text(200),
        ];
    }

    /**
     * Configure the model factory to create an input operation.
     */
    public function input(): static
    {
        return $this->state(fn (array $attributes) => [
            'operation_type' => OperationTypeEnum::INPUT,
        ]);
    }

    /**
     * Configure the model factory to create an output operation.
     */
    public function output(): static
    {
        return $this->state(fn (array $attributes) => [
            'operation_type' => OperationTypeEnum::OUTPUT,
        ]);
    }
}
