<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaidBill>
 */
class PaidBillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_value' => $this->faker->randomFloat(2, 100, 10000),
            'invoice_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'operation_id' => \App\Models\Operation::factory(),
        ];
    }

    /**
     * Set a specific operation ID.
     *
     * @param int $operationId
     * @return static
     */
    public function forOperation($operationId)
    {
        return $this->state(fn(array $attributes) => [
            'operation_id' => $operationId,
        ]);
    }

    /**
     * Set a specific invoice value.
     *
     * @param float $value
     * @return static
     */
    public function withValue($value)
    {
        return $this->state(fn(array $attributes) => [
            'invoice_value' => $value,
        ]);
    }
}
