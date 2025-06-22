<?php

namespace App\Models;

use App\Enums\OperationTypeEnum;
use App\Traits\HasOperations;
use App\Traits\HasStatistics;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    /** @use HasFactory<\Database\Factories\OperationFactory> */
    use HasFactory, HasOperations, HasStatistics;

    protected $fillable = [
        'partner_id',
        'customer_name',//اسم العميل
        'operation_type',//نوع العملية
        'invoice_number',//رقم الفاتورة
        'invoice_value',//قيمة الفاتورة
        'percentage_of_bill',//نسبتي من المبلغ
        'invoice_date',//تاريخ الفاتورة
        'alert_date',//تاريخ التنبيه
        'comments',//الملاحظات
    ];
    protected $casts = [
        'operation_type' => OperationTypeEnum::class,
        'invoice_date' => 'datetime',
        'alert_date' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        //statistics
        'total_invoice_value', // مجاميع قيم الفواتير
        'total_paid_bills_total_value', // مجاميع مجموع المبالغ المسددة
        'total_remaining_of_bill_value', // مجاميع البواقي من الفاتورة
        'total_amount_due_value', // مجاميع المبالغ المستحقة
        'total_received_amounts_total_value', // مجاميع المبالغ المقبوضة
        'total_remaining_amount_value', // مجاميع المبالغ المتبقي
        'total_percentage_value', // مجاميع قيم النسب
        //operations
        'percentage_value',// قيمة النسبة المئوية
        'remaining_of_bill_value',// الباقي من الفاتورة
        'amount_due_value',// المبلغ المستحق
        'remaining_amount_value',// المبلغ المتبقي
        'paid_bills_total',// المبالغ المسددة
        'received_amounts_total',// المبالغ المقبوضة
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
}
