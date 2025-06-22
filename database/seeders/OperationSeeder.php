<?php

namespace Database\Seeders;

use App\Enums\OperationTypeEnum;
use App\Models\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operation::create([
            'partner_id' => '1',
            'customer_name' => 'AKA',
            'operation_type' => OperationTypeEnum::INPUT,
            'invoice_number' => '961',
            'invoice_value' => 10000,
            'percentage_of_bill' => 7,
            'invoice_date' => now(),
            'alert_date' => now()->addDays(10),
            'comments' => 'تم تنفيذ وساطة بين شركتنا و AKA لبيع عقار سكني.  نحتفظ بكامل تفاصيل الاتفاق والمرفقات ذات الصلة ضمن سجلاتنا.  العملية مسجلة لدينا كـ "قيد التحصيل"',
        ]);

        Operation::create([
            'partner_id' => '2',
            'customer_name' => 'JAWAD',
            'operation_type' => OperationTypeEnum::INPUT,
            'invoice_number' => '981',
            'invoice_value' => 20000,
            'percentage_of_bill' => 10,
            'invoice_date' => now()->addDays(5),
            'alert_date' => now()->addDays(15),
            'comments' => 'تم تنفيذ وساطة بين شركتنا و AKA لبيع عقار سكني.  نحتفظ بكامل تفاصيل الاتفاق والمرفقات ذات الصلة ضمن سجلاتنا.  العملية مسجلة لدينا كـ "قيد التحصيل"',
        ]);

        Operation::create([
            'partner_id' => '1',
            'customer_name' => 'AKA',
            'operation_type' => OperationTypeEnum::OUTPUT,
            'invoice_number' => '961',
            'invoice_value' => 1000,
            'percentage_of_bill' => 7,
            'invoice_date' => now(),
            'alert_date' => now()->addDays(10),
            'comments' => 'تم تنفيذ وساطة بين شركتنا و AKA لبيع عقار سكني.  نحتفظ بكامل تفاصيل الاتفاق والمرفقات ذات الصلة ضمن سجلاتنا.  العملية مسجلة لدينا كـ "قيد التحصيل"',
        ]);

        Operation::create([
            'partner_id' => '2',
            'customer_name' => 'JAWAD',
            'operation_type' => OperationTypeEnum::OUTPUT,
            'invoice_number' => '981',
            'invoice_value' => 2000,
            'percentage_of_bill' => 10,
            'invoice_date' => now()->addDays(5),
            'alert_date' => now()->addDays(15),
            'comments' => 'تم تنفيذ وساطة بين شركتنا و AKA لبيع عقار سكني.  نحتفظ بكامل تفاصيل الاتفاق والمرفقات ذات الصلة ضمن سجلاتنا.  العملية مسجلة لدينا كـ "قيد التحصيل"',
        ]);

//        Operation::factory(10)->create();
    }
}
