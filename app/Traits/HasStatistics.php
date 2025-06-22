<?php

namespace App\Traits;

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
            'لشريك واحد' => (float)$this->where('partner_id', $this->partner_id)->sum('invoice_value'),
            'لحميع الشراكات' => (float)$this->sum('invoice_value'),
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
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('paid_bills_total')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('paid_bills_total'),
        ];
    }

    protected function getTotalRemainingOfBillValueAttribute(): array
    {
        return [
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('remaining_of_bill_value')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('remaining_of_bill_value'),
        ];
    }

    protected function getTotalAmountDueValueAttribute(): array
    {
        return [
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('amount_due_value')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('amount_due_value'),
        ];
    }

    protected function getTotalReceivedAmountsTotalValueAttribute(): array
    {
        return [
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('received_amounts_total')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('received_amounts_total'),
        ];
    }

    protected function getTotalRemainingAmountValueAttribute(): array
    {
        return [
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('remaining_amount_value')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('remaining_amount_value'),
        ];
    }

    protected function getTotalPercentageValueAttribute(): array
    {
        return [
            // Calculate for single partner if partner_id is set
            'لشريك واحد' => $this->partner_id
                ? (float)\App\Models\Operation::where('partner_id', $this->partner_id)
                    ->get()
                    ->sum('percentage_value')
                : 0.0,
            // Calculate for all partners
            'لحميع الشراكات' => (float)\App\Models\Operation::with(['paidBills'])
                ->get()
                ->sum('percentage_value'),
        ];
    }

}
