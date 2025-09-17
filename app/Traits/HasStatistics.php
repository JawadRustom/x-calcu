<?php

namespace App\Traits;

use App\Enums\OperationTypeEnum;
use App\Models\Operation;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasStatistics
{
    //'total_invoice_value', // مجاميع قيم الفواتير
    //'total_paid_bills_total_value', // مجاميع مجموع المبالغ المسددة
    //'total_remaining_of_bill_value', // مجاميع البواقي من الفاتورة
    //'total_amount_due_value', // مجاميع المبالغ المستحقة
    //'total_received_amounts_total_value', // مجاميع المبالغ المقبوضة
    //'total_remaining_amount_value', // مجاميع المبالغ المتبقي
    //'total_percentage_value', // مجاميع قيم النسب
    // مجاميع قيم الفواتير للشريك
    protected function getTotalInvoiceValueAttribute(): array
    {
        return [
            'input_operation' => [
                'لشريك واحد' => (float)$this->where('operation_type', OperationTypeEnum::INPUT)->where('partner_id', $this->partner_id)->sum('invoice_value'),
                'لحميع الشراكات' => (float)$this->where('operation_type', OperationTypeEnum::INPUT)->whereHas('partner.user', function ($query) {
                    $query->where('user_id', auth()->id());
                })->sum('invoice_value'),
            ],
            'output_operation' => [
                'لشريك واحد' => (float)$this->where('operation_type', OperationTypeEnum::OUTPUT)->where('partner_id', $this->partner_id)->sum('invoice_value'),
                'لحميع الشراكات' => (float)$this->where('operation_type', OperationTypeEnum::OUTPUT)->whereHas('partner.user', function ($query) {
                    $query->where('user_id', auth()->id());
                })->sum('invoice_value'),
            ],
        ];
    }

    /**
     * Get the total paid bills amount for both a single partner and all partners
     *
     * @return array<string, float>
     */
    protected function getTotalPaidBillsTotalValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('paid_bills_total')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('paid_bills_total'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('paid_bills_total')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('paid_bills_total'),
            ],
        ];
    }

    protected function getTotalRemainingOfBillValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('remaining_of_bill_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('remaining_of_bill_value'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('remaining_of_bill_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('remaining_of_bill_value'),
            ],
        ];
    }

    protected function getTotalAmountDueValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('amount_due_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('amount_due_value'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('amount_due_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('amount_due_value'),
            ],
        ];
    }

    protected function getTotalReceivedAmountsTotalValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('received_amounts_total')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('received_amounts_total'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('received_amounts_total')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('received_amounts_total'),
            ],
        ];
    }

    protected function getTotalRemainingAmountValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('remaining_amount_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('remaining_amount_value'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->whereHas('partner.user', function ($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('remaining_amount_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('remaining_amount_value'),
            ],
        ];
    }

    protected function getTotalPercentageValueAttribute(): array
    {
        return [
            'input_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::INPUT)
                        ->get()
                        ->sum('percentage_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::INPUT)
                    ->get()
                    ->sum('percentage_value'),
            ],
            'output_operation' => [
                // Calculate for single partner if partner_id is set
                'لشريك واحد' => $this->partner_id
                    ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                        ->where('operation_type', OperationTypeEnum::OUTPUT)
                        ->get()
                        ->sum('percentage_value')
                    : 0.0,
                // Calculate for all partners
                'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                    ->whereHas('partner.user', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->where('operation_type', OperationTypeEnum::OUTPUT)
                    ->get()
                    ->sum('percentage_value'),
            ],
        ];
    }

}
