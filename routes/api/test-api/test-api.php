<?php

\Illuminate\Support\Facades\Route::get('/test', function (Request $request) {
    $operationForAllParents = \App\Models\Operation::whereHas('partner', static fn($q) => $q->whereHas('user', static fn($q) => $q->where('user_id', 1)
    )
    )->where('partner_id', '=', 1)->first();
    $operationForOneParent = \App\Models\Operation::whereHas('partner', static fn($q) => $q->whereHas('user', static fn($q) => $q->where('user_id', 1)
    )
    )->where('partner_id', '=', 1)->first();

    //Operation Input Statistics For One Parent Page
    $operationInputStatisticsForOneParentPage = [
        'مجاميع قيم الفواتير' => $operationForOneParent->total_invoice_value['input_operation']['لشريك واحد'],
        'مجاميع سداد الفواتير' => $operationForOneParent->total_paid_bills_total_value['input_operation']['لشريك واحد'],
        'باقي الفواتير' => $operationForOneParent->total_remaining_of_bill_value['input_operation']['لشريك واحد'],
        'المبلغ المستحقة' => $operationForOneParent->total_amount_due_value['input_operation']['لشريك واحد'],
        'المبلغ المقبوضة' => $operationForOneParent->total_received_amounts_total_value['input_operation']['لشريك واحد'],
        'المبلغ المتبقية' => $operationForOneParent->total_remaining_amount_value['input_operation']['لشريك واحد'],
        'الارباح' => $operationForOneParent->total_percentage_value['input_operation']['لشريك واحد'],
    ];

    //Operation Output Statistics For One Parent Page
    $operationOutputStatisticsForOneParentPage = [
        'مجاميع قيم الفواتير' => $operationForOneParent->total_invoice_value['output_operation']['لشريك واحد'],
        'مجاميع سداد الفواتير' => $operationForOneParent->total_paid_bills_total_value['output_operation']['لشريك واحد'],
        'باقي الفواتير' => $operationForOneParent->total_remaining_of_bill_value['output_operation']['لشريك واحد'],
        'المبلغ المستحقة' => $operationForOneParent->total_amount_due_value['output_operation']['لشريك واحد'],
        'المبلغ المقبوضة' => $operationForOneParent->total_received_amounts_total_value['output_operation']['لشريك واحد'],
        'المبلغ المتبقية' => $operationForOneParent->total_remaining_amount_value['output_operation']['لشريك واحد'],
        'الارباح' => $operationForOneParent->total_percentage_value['output_operation']['لشريك واحد'],
    ];

    //Operation Input Statistics For All Parents Page
    $operationInputStatisticsForAllParentsPage = [
        'مجاميع قيم الفواتير' => $operationForAllParents->total_invoice_value['input_operation']['لحميع الشراكات'],
        'مجاميع سداد الفواتير' => $operationForAllParents->total_paid_bills_total_value['input_operation']['لحميع الشراكات'],
        'باقي الفواتير' => $operationForAllParents->total_remaining_of_bill_value['input_operation']['لحميع الشراكات'],
        'المبلغ المستحقة' => $operationForAllParents->total_amount_due_value['input_operation']['لحميع الشراكات'],
        'المبلغ المقبوضة' => $operationForAllParents->total_received_amounts_total_value['input_operation']['لحميع الشراكات'],
        'المبلغ المتبقية' => $operationForAllParents->total_remaining_amount_value['input_operation']['لحميع الشراكات'],
        'الارباح' => $operationForAllParents->total_percentage_value['input_operation']['لحميع الشراكات'],
    ];

    //Operation Output Statistics For All Parents Page
    $operationOutputStatisticsForAllParentsPage = [
        'مجاميع قيم الفواتير' => $operationForAllParents->total_invoice_value['output_operation']['لحميع الشراكات'],
        'مجاميع سداد الفواتير' => $operationForAllParents->total_paid_bills_total_value['output_operation']['لحميع الشراكات'],
        'باقي الفواتير' => $operationForAllParents->total_remaining_of_bill_value['output_operation']['لحميع الشراكات'],
        'المبلغ المستحقة' => $operationForAllParents->total_amount_due_value['output_operation']['لحميع الشراكات'],
        'المبلغ المقبوضة' => $operationForAllParents->total_received_amounts_total_value['output_operation']['لحميع الشراكات'],
        'المبلغ المتبقية' => $operationForAllParents->total_remaining_amount_value['output_operation']['لحميع الشراكات'],
        'الارباح' => $operationForAllParents->total_percentage_value['output_operation']['لحميع الشراكات'],
    ];

//    // figma page
//    $figmaPage = [
//        'اسم الشريك' => $operation->partner->name,
//        'اسم العميل' => $operation->customer_name,
//        'نوع العملية' => $operation->operation_type->value,
//        'رقم الفاتورة' => $operation->invoice_number,
//        'قيمة الفاتورة' => $operation->invoice_value,
//        'سدد من الفاتورة' => [
//            'قيمة السداد الكلية' => $operation->paid_bills_total,
//            'القيم التفصيلية' => $operation->paidBills->map(fn($paidBills) => [
//                'invoice_value' => $paidBills->invoice_value,
//                'invoice_date' => $paidBills->invoice_date->format('Y-m-d H:i:s')
//            ])->toArray(),
//        ],
//        'باقي من الفاتورة' => $operation->remaining_of_bill_value,
//        'نسبتي من المبلغ' => [
//            'النسبة المئوية' => $operation->percentage_of_bill . '%',
//            'قيمة النسبة المئوية' => $operation->percentage_value,
//        ],
//        'المبلغ المستحق' => $operation->amount_due_value,
//        'المبلغ المقبوض' => [
//            'قيمة المقبوضات الكلية' => $operation->received_amounts_total,
//            'القيم التفصيلية' => $operation->receivedAmounts->map(fn($receivedAmounts) => [
//                'invoice_value' => $receivedAmounts->invoice_value,
//                'invoice_date' => $receivedAmounts->invoice_date->format('Y-m-d H:i:s')
//            ])->toArray(),
//        ],
//        'المبلغ المتبقي' => $operation->remaining_amount_value,
//        'التاريخ' => $operation->invoice_date->format('Y-m-d H:i:s'),
//        'تاريخ التنبيه' => $operation->alert_date->format('Y-m-d H:i:s'),
//        'الملاحظات' => $operation->comments,
//    ];
//
//    // whatsapp page
//    $whatsappPage = [
//        'اسم الشريك' => $operation->partner->name,
//        'العميل' => $operation->customer_name,
//        'رقم الفاتورة' => $operation->invoice_number,
//        'قيمة الفاتورة' => $operation->invoice_value,
//        'سدد من الفاتورة' => $operation->paid_bills_total,
//        'باقي من الفاتورة' => $operation->remaining_of_bill_value,
//        'نسبتي من المبلغ' => $operation->percentage_of_bill . '%',
//        'المبلغ المستحق' => $operation->amount_due_value,
//        'قبوض' => $operation->received_amounts_total,
//        'باقي' => $operation->remaining_amount_value,
//        'الملاحظات' => $operation->comments,
//        'التاريخ' => $operation->invoice_date->format('Y-m-d H:i:s'),
//    ];

    return [
//        'figma' => $figmaPage,
//        'whatsapp' => $whatsappPage,
        'operation_input_statistics_for_one_parent_page' => $operationInputStatisticsForOneParentPage,
        'operation_output_statistics_for_one_parent_page' => $operationOutputStatisticsForOneParentPage,
        'operation_input_statistics_for_all_parents_page' => $operationInputStatisticsForAllParentsPage,
        'operation_output_statistics_for_all_parents_page' => $operationOutputStatisticsForAllParentsPage
    ];
});
