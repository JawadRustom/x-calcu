<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the type of operation in the system.
 */
enum OperationTypeEnum: string
{
    case INPUT = 'Input';
    case OUTPUT = 'Output';

    /**
     * Convert the enum cases to an associative array.
     *
     * @return array<string, string> Array with values as keys and names as values
     */
    public static function toArray(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'name')
        );
    }

    /**
     * Check if the current instance is an input operation.
     */
    public function isInput(): bool
    {
        return $this === self::INPUT;
    }

    /**
     * Check if the current instance is an output operation.
     */
    public function isOutput(): bool
    {
        return $this === self::OUTPUT;
    }

    /**
     * Get the opposite operation type.
     */
    public function opposite(): self
    {
        return match ($this) {
            self::INPUT => self::OUTPUT,
            self::OUTPUT => self::INPUT,
        };
    }
}
