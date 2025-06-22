<?php

\Illuminate\Support\Facades\Route::get('/test', function (Request $request) {
    $operation = \App\Models\Operation::find(1);

    //Statistics page
    $statisticsPage = [
        'مجاميع قيم الفواتير' => $operation->total_invoice_value,
        'مجاميع سداد الفواتير' => $operation->total_paid_bills_total_value,
        'باقي الفواتير' => $operation->total_remaining_of_bill_value,
        'المبلغ المستحقة' => $operation->total_amount_due_value,
        'المبلغ المقبوضة' => $operation->total_received_amounts_total_value,
        'المبلغ المتبقية' => $operation->total_remaining_amount_value,
        'الارباح' => $operation->total_percentage_value,
    ];

    // figma page
    $figmaPage = [
        'اسم الشريك' => $operation->partner->name,
        'اسم العميل' => $operation->customer_name,
        'نوع العملية' => $operation->operation_type->value,
        'رقم الفاتورة' => $operation->invoice_number,
        'قيمة الفاتورة' => $operation->invoice_value,
        'سدد من الفاتورة' => [
            'قيمة السداد الكلية' => $operation->paid_bills_total,
            'القيم التفصيلية' => $operation->paidBills->map(fn($paidBills) => [
                'invoice_value' => $paidBills->invoice_value,
                'invoice_date' => $paidBills->invoice_date->format('Y-m-d H:i:s')
            ])->toArray(),
        ],
        'باقي من الفاتورة' => $operation->remaining_of_bill_value,
        'نسبتي من المبلغ' => [
            'النسبة المئوية' => $operation->percentage_of_bill . '%',
            'قيمة النسبة المئوية' => $operation->percentage_value,
        ],
        'المبلغ المستحق' => $operation->amount_due_value,
        'المبلغ المقبوض' => [
            'قيمة المقبوضات الكلية' => $operation->received_amounts_total,
            'القيم التفصيلية' => $operation->receivedAmounts->map(fn($receivedAmounts) => [
                'invoice_value' => $receivedAmounts->invoice_value,
                'invoice_date' => $receivedAmounts->invoice_date->format('Y-m-d H:i:s')
            ])->toArray(),
        ],
        'المبلغ المتبقي' => $operation->remaining_amount_value,
        'التاريخ' => $operation->invoice_date->format('Y-m-d H:i:s'),
        'تاريخ التنبيه' => $operation->alert_date->format('Y-m-d H:i:s'),
        'الملاحظات' => $operation->comments,
    ];

    // whatsapp page
    $whatsappPage = [
        'اسم الشريك' => $operation->partner->name,
        'العميل' => $operation->customer_name,
        'رقم الفاتورة' => $operation->invoice_number,
        'قيمة الفاتورة' => $operation->invoice_value,
        'سدد من الفاتورة' => $operation->paid_bills_total,
        'باقي من الفاتورة' => $operation->remaining_of_bill_value,
        'نسبتي من المبلغ' => $operation->percentage_of_bill . '%',
        'المبلغ المستحق' => $operation->amount_due_value,
        'قبوض' => $operation->received_amounts_total,
        'باقي' => $operation->remaining_amount_value,
        'الملاحظات' => $operation->comments,
        'التاريخ' => $operation->invoice_date->format('Y-m-d H:i:s'),
    ];

    return [
        'figma' => $figmaPage,
        'whatsapp' => $whatsappPage,
        'statistics' => $statisticsPage
    ];
});
