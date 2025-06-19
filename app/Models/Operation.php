<?php

namespace App\Models;

use App\Enums\OperationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    /** @use HasFactory<\Database\Factories\OperationFactory> */
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'customer_name',
        'operation_type',
        'invoice_number',
        'invoice_value',
        'remaining_of_bill',
        'percentage_of_bill',
        'amount_due',
        'remaining_amount',
        'invoice_date',
        'alert_date',
        'comments',
    ];
    protected $casts = [
        'operation_type' => OperationTypeEnum::class,
        'invoice_date' => 'datetime',
        'alert_date' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Partner::class);
    }

    public function paidBills(): HasMany
    {
        return $this->hasMany(\App\Models\PaidBill::class);
    }

    public function receivedAmounts(): HasMany
    {
        return $this->hasMany(\App\Models\ReceivedAmount::class);
    }

    /**
     * Get the calculated percentage value based on invoice_value and percentage_of_bill.
     *
     * @return float
     */
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
    protected function getPaidBillsTotalAttribute(): float
    {
        return (float)$this->paidBills()->sum('invoice_value');
    }

    /**
     * Get the sum of all related received amounts' invoice values.
     *
     * @return float
     */
    protected function getReceivedAmountsTotalAttribute(): float
    {
        return (float)$this->receivedAmounts()->sum('invoice_value');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'percentage_value',// قيمة النسبة المئوية
        'remaining_of_bill_value',// الباقي من الفاتورة
        'amount_due_value',// المبلغ المستحق
        'remaining_amount_value',// المبلغ المتبقي
        'paid_bills_total',// المبالغ المسددة
        'received_amounts_total',// المبالغ المقبوضة
    ];
}
