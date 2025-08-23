<?php

namespace App\Helpers;

use App\Models\Operation;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class StatisticHelper
{
    private const OPERATION_TYPES = ['input', 'output'];
    private const PARTNER_TYPES = [
        'single' => 'لشريك واحد',
        'all' => 'لحميع الشراكات',
    ];

    private const STATISTIC_KEYS = [
        'total_invoice_value' => 'مجاميع قيم الفواتير',
        'total_paid_bills_total_value' => 'مجاميع سداد الفواتير',
        'total_remaining_of_bill_value' => 'باقي الفواتير',
        'total_amount_due_value' => 'المبلغ المستحقة',
        'total_received_amounts_total_value' => 'المبلغ المقبوضة',
        'total_remaining_amount_value' => 'المبلغ المتبقية',
        'total_percentage_value' => 'الارباح',
    ];

    public static function getStatistics(?int $parentId, string $operationType): array
    {
        self::validateOperationType($operationType);
        $operation = self::getOperation($parentId);
        $partnerType = $parentId ? 'single' : 'all';
        $operationTypeKey = "{$operationType}_operation";
        
        return self::generateStatisticsArray($operation, $operationTypeKey, $partnerType);
    }

    private static function validateOperationType(string $operationType): void
    {
        if (!in_array($operationType, self::OPERATION_TYPES, true)) {
            throw new InvalidArgumentException('Operation type must be either "input" or "output"');
        }
    }

    private static function getOperation(?int $parentId): Operation
    {
        $query = Operation::whereHas('partner.user', function ($query) {
            $query->where('user_id', Auth::id());
        });

        if ($parentId) {
            return $query->where('partner_id', $parentId)->firstOrFail();
        }

        return $query->where('partner_id', Auth::user()->partners()->firstOrFail()->id)
            ->firstOrFail();
    }

    private static function generateStatisticsArray(
        Operation $operation,
        string $operationTypeKey,
        string $partnerType
    ): array {
        $statistics = [];
        
        foreach (self::STATISTIC_KEYS as $key => $label) {
            $statistics[$label] = $operation->$key[$operationTypeKey][self::PARTNER_TYPES[$partnerType]];
        }
        
        return $statistics;
    }
}
