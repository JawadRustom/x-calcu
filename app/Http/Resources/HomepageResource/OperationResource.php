<?php

namespace App\Http\Resources\HomepageResource;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'اسم الشريك' => $this->partner->name,
            'اسم العميل' => $this->customer_name,
            'نوع العملية' => $this->operation_type->value,
            'رقم الفاتورة' => $this->invoice_number,
            'قيمة الفاتورة' => $this->invoice_value,
            'سدد من الفاتورة' => [
                'قيمة السداد الكلية' => $this->paid_bills_total,
                'القيم التفصيلية' => $this->paidBills->map(fn($paidBills) => [
                    'invoice_value' => $paidBills->invoice_value,
                    'invoice_date' => $paidBills->invoice_date ? $paidBills->invoice_date->format('Y-m-d H:i:s') : null,
                ])->toArray(),
            ],
            'باقي من الفاتورة' => $this->remaining_of_bill_value,
            'نسبتي من المبلغ' => [
                'النسبة المئوية' => $this->percentage_of_bill . '%',
                'قيمة النسبة المئوية' => $this->percentage_value,
            ],
            'المبلغ المستحق' => $this->amount_due_value,
            'المبلغ المقبوض' => [
                'قيمة المقبوضات الكلية' => $this->received_amounts_total,
                'القيم التفصيلية' => $this->receivedAmounts->map(fn($receivedAmounts) => [
                    'invoice_value' => $receivedAmounts->invoice_value,
                    'invoice_date' => $receivedAmounts->invoice_date ? $receivedAmounts->invoice_date->format('Y-m-d H:i:s') : null,
                ])->toArray(),
            ],
            'المبلغ المتبقي' => $this->remaining_amount_value,
            'التاريخ' => $this->invoice_date ? $this->invoice_date->format('Y-m-d H:i:s') : null,
            'تاريخ التنبيه' => $this->alert_date ? $this->alert_date->format('Y-m-d H:i:s') : null,
            'الملاحظات' => $this->comments,
        ];
    }
}
