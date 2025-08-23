<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatisticRequest;
use App\Models\Operation;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getStatistic(StatisticRequest $request): array
    {
        if ($request['parentId']) {
            $operation = \App\Models\Operation::whereHas('partner', static fn($q) => $q->whereHas('user', static fn($q) => $q->where('user_id', 1)
            )
            )->where('partner_id', '=', $request['parentId'])->first();
            if ($request['operationType'] === 'input') {
                return [
                    'مجاميع قيم الفواتير' => $operation->total_invoice_value['input_operation']['لشريك واحد'],
                    'مجاميع سداد الفواتير' => $operation->total_paid_bills_total_value['input_operation']['لشريك واحد'],
                    'باقي الفواتير' => $operation->total_remaining_of_bill_value['input_operation']['لشريك واحد'],
                    'المبلغ المستحقة' => $operation->total_amount_due_value['input_operation']['لشريك واحد'],
                    'المبلغ المقبوضة' => $operation->total_received_amounts_total_value['input_operation']['لشريك واحد'],
                    'المبلغ المتبقية' => $operation->total_remaining_amount_value['input_operation']['لشريك واحد'],
                    'الارباح' => $operation->total_percentage_value['input_operation']['لشريك واحد'],
                ];
            }
            return [
                'مجاميع قيم الفواتير' => $operation->total_invoice_value['output_operation']['لشريك واحد'],
                'مجاميع سداد الفواتير' => $operation->total_paid_bills_total_value['output_operation']['لشريك واحد'],
                'باقي الفواتير' => $operation->total_remaining_of_bill_value['output_operation']['لشريك واحد'],
                'المبلغ المستحقة' => $operation->total_amount_due_value['output_operation']['لشريك واحد'],
                'المبلغ المقبوضة' => $operation->total_received_amounts_total_value['output_operation']['لشريك واحد'],
                'المبلغ المتبقية' => $operation->total_remaining_amount_value['output_operation']['لشريك واحد'],
                'الارباح' => $operation->total_percentage_value['output_operation']['لشريك واحد'],
            ];
        }
        $operation = \App\Models\Operation::whereHas('partner', static fn($q) => $q->whereHas('user', static fn($q) => $q->where('user_id', 1)
        )
        )->where('partner_id', '=', 1)->first();
        if ($request['operationType'] === 'input') {
            return [
                'مجاميع قيم الفواتير' => $operation->total_invoice_value['input_operation']['لحميع الشراكات'],
                'مجاميع سداد الفواتير' => $operation->total_paid_bills_total_value['input_operation']['لحميع الشراكات'],
                'باقي الفواتير' => $operation->total_remaining_of_bill_value['input_operation']['لحميع الشراكات'],
                'المبلغ المستحقة' => $operation->total_amount_due_value['input_operation']['لحميع الشراكات'],
                'المبلغ المقبوضة' => $operation->total_received_amounts_total_value['input_operation']['لحميع الشراكات'],
                'المبلغ المتبقية' => $operation->total_remaining_amount_value['input_operation']['لحميع الشراكات'],
                'الارباح' => $operation->total_percentage_value['input_operation']['لحميع الشراكات'],
            ];
        }
        return [
            'مجاميع قيم الفواتير' => $operation->total_invoice_value['output_operation']['لحميع الشراكات'],
            'مجاميع سداد الفواتير' => $operation->total_paid_bills_total_value['output_operation']['لحميع الشراكات'],
            'باقي الفواتير' => $operation->total_remaining_of_bill_value['output_operation']['لحميع الشراكات'],
            'المبلغ المستحقة' => $operation->total_amount_due_value['output_operation']['لحميع الشراكات'],
            'المبلغ المقبوضة' => $operation->total_received_amounts_total_value['output_operation']['لحميع الشراكات'],
            'المبلغ المتبقية' => $operation->total_remaining_amount_value['output_operation']['لحميع الشراكات'],
            'الارباح' => $operation->total_percentage_value['output_operation']['لحميع الشراكات'],
        ];
    }
}
