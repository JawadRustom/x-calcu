<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasOperations
{

    //'percentage_value',// قيمة النسبة المئوية
    //'remaining_of_bill_value',// الباقي من الفاتورة
    //'amount_due_value',// المبلغ المستحق
    //'remaining_amount_value',// المبلغ المتبقي
    //'paid_bills_total',// المبالغ المسددة
    //'received_amounts_total',// المبالغ المقبوضة

    /**
     * Get the calculated percentage value based on invoice_value and percentage_of_bill.
     *
     * @return float
     */
    // قدر النسبة المئوية من البالغ المسددة
    protected function getPercentageValueAttribute(): float
    {
        if (empty($this->paid_bills_total) || empty($this->percentage_of_bill)) {
            return 0.0;
        }

        return ($this->paid_bills_total * $this->percentage_of_bill) / 100;
    }

    // الباقي من الفاتورة = قيمة الفاتورة - المبالغ المسددة

    protected function getRemainingOfBillValueAttribute(): float
    {
        if (empty($this->invoice_value) || empty($this->paid_bills_total)) {
            return 0.0;
        }

        return $this->invoice_value - $this->paid_bills_total;
    }

    // المبلغ المستحق = المبالغ المسددة - النسبة

    protected function getAmountDueValueAttribute(): float
    {
        if (empty($this->paid_bills_total) || empty($this->percentage_value)) {
            return 0.0;
        }

        return $this->paid_bills_total - $this->percentage_value;
    }

    // المبلغ المتبقي = المبلغ المستحق - المبالغ المقبوضة


    protected function getRemainingAmountValueAttribute(): float
    {
        if (empty($this->amount_due_value) || empty($this->received_amounts_total)) {
            return 0.0;
        }

        return $this->amount_due_value - $this->received_amounts_total;
    }
    /**
     * Get the sum of all related paid bills' invoice values.
     *
     * @return float
     */
    // مجموع المبالغ المسددة
    protected function getPaidBillsTotalAttribute(): float
    {
        return (float)$this->paidBills()->sum('invoice_value');
    }

    /**
     * Get the sum of all related received amounts' invoice values.
     *
     * @return float
     */
    // مجموع المبالغ المقبوضة
    protected function getReceivedAmountsTotalAttribute(): float
    {
        return (float)$this->receivedAmounts()->sum('invoice_value');
    }
}
