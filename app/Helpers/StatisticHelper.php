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
        self::validateParentId($parentId);
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

    private static function validateParentId(?int $parentId): void
    {
        // Skip validation if parentId is null (for all partners case)
        if ($parentId === null) {
            return;
        }

        // Get all partner IDs for the authenticated user
        $partnerIds = Auth::user()->partners()->pluck('id')->toArray();

        // If user has no partners, throw an exception
        if (empty($partnerIds)) {
            throw new \Exception('You don\'t have any partners');
        }

        // Check if the provided parentId belongs to user's partners
        if (!in_array($parentId, $partnerIds)) {
            throw new \Exception('You don\'t have access to this partner');
        }
    }

    private static function getOperation(?int $parentId)
    {
        $query = Operation::whereHas('partner.user', function ($query) {
            $query->where('user_id', Auth::id());
        });

        if ($parentId) {
            return $query->where('partner_id', $parentId)->firstOrNew(['partner_id' => $parentId]);
        }

        $partner = Auth::user()->partners()->first();
        if (!$partner) {
            return new Operation();
        }

        return $query->where('partner_id', $partner->id)->firstOrNew(['partner_id' => $partner->id]);
    }

    private static function generateStatisticsArray(
        Operation $operation,
        string    $operationTypeKey,
        string    $partnerType
    ): array
    {
        $statistics = [];

        foreach (self::STATISTIC_KEYS as $key => $label) {
            $statistics[$label] = $operation->$key[$operationTypeKey][self::PARTNER_TYPES[$partnerType]];
        }

        return $statistics;
    }
}
