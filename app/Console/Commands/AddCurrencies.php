<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class AddCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Test Currencies in Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('currencies')->insert([
            ['name' => 'شيكل', 'code' => 'ILS', 'sign' => '₪', 'default' => '1', 'organization_id' => '2', 'created_by' => '2', 'created_at' => date("Y/m/d")],
            ['name' => 'دولار', 'code' => 'USD', 'sign' => '$', 'default' => '1', 'organization_id' => '2', 'created_by' => '2', 'created_at' => date("Y/m/d")],
            ['name' => 'دينار اردني', 'code' => 'JOD', 'sign' => 'JOD', 'default' => '0', 'organization_id' => '1', 'created_by' => '1', 'created_at' => date("Y/m/d")]
        ]);

        DB::table('organization_currencies')->insert([
            ['currency_id' => '1', 'organization_id' => '1'],
        ]);

        DB::table('exhange_rates')->insert([
            ['from' => '2', 'to' => '1', 'organization_id' => '2','value' => '3.45', 'default' => true, 'created_by' => '1', 'updated_by' => '1'],
            ['from' => '1', 'to' => '2', 'organization_id' => '2','value' => '0.289', 'default' => true, 'created_by' => '1', 'updated_by' => '1'],
            ['from' => '2', 'to' => '3', 'organization_id' => '2','value' => '0.71', 'default' => true, 'created_by' => '1', 'updated_by' => '1'],
            ['from' => '3', 'to' => '2', 'organization_id' => '2','value' => '1.41', 'default' => true, 'created_by' => '1', 'updated_by' => '1'],
            ['from' => '2', 'to' => '1', 'organization_id' => '1','value' => '3.8', 'default' => false, 'created_by' => '1', 'updated_by' => '1'],
            ['from' => '1', 'to' => '2', 'organization_id' => '1','value' => '0.26', 'default' => false, 'created_by' => '1', 'updated_by' => '1'],
        ]);

        DB::table('settings')->insert([
            ['currency_id' => 1, 'prepare_duration' => '30', 'user_id' => '2']
        ]);

        DB::table('weekdays')->insert([
            ['organization_id' => 1, 'saturday' => false, 'sunday' => true, 'monday' => true, "tuesday" => true, "wednesday" => true, 'thursday' => true]
        ]);
        
        DB::table('customers')->insert([
            ['name' => "محمد احمد", 'phone1' => "0597143212", 'code' => 'TDS-0001', 'part_number' => 2, 'organization_id' => 1,"address_id" => 1, 'created_by' => 1, 'updated_by' => 1]
        ]);

        DB::table('reservations')->insert([
            ['organization_id' => 1, 'code'=> 'R-2020-DSA1', 'part_number'=> '1', 'title' => "حفل زفاف محمد احمد", 'customer_name' => 'محمد احمد', 'customer_id' => 1, 'currency_id' => 1, 'created_by' => 1, 'updated_by'=> 1],
            ['organization_id' => 1, 'code'=> 'R-2020-DSA2', 'part_number'=> '2',  'title' => "حفل تخرج توجيهي", 'customer_name' => 'محمد احمد', 'customer_id' => 1, 'currency_id' => 1, 'created_by' => 1, 'updated_by'=> 1]
        ]);

        DB::table('halls')->insert([
            ['name' => "القاعة الملكية", 'price' => "40", 'capacity' => '300','currency_id' => 1, 'organization_id' => 1],
            ['name' => "القاعة السفلية", 'price' => "80", 'capacity' => '300','currency_id' => 1, 'organization_id' => 1],
        ]);

        DB::table('reservation_halls')->insert([
            ['hall_id' => 1, 'reservation_id' => 1],
            ['hall_id' => 2, 'reservation_id' => 2],
        ]);

        DB::table('events')->insert([
            ['from_time' => "08:00", 'to_time' => "13:30", 'reservation_date' => '2020-07-09', 'reservation_id' => 1, "type" => 1, 'hall_id' => 1, 'related_id' => null],
            ['from_time' => "07:30", 'to_time' => "08:00", 'reservation_date' => '2020-07-09', 'reservation_id' => 1, "type" => 0, 'hall_id' => 1, 'related_id' => 1],
            ['from_time' => "08:00", 'to_time' => "13:30", 'reservation_date' => '2020-07-30', 'reservation_id' => 1, "type" => 1, 'hall_id' => 1, 'related_id' => null],
            ['from_time' => "07:30", 'to_time' => "08:00", 'reservation_date' => '2020-07-30', 'reservation_id' => 1, "type" => 0, 'hall_id' => 1, 'related_id' => 3],
            ['from_time' => "15:00", 'to_time' => "18:30", 'reservation_date' => '2020-07-15', 'reservation_id' => 2, "type" => 1, 'hall_id' => 2, 'related_id' => null],
        ]);

        DB::table('eventlist')->insert([
            ['name' => "وقت دخول العرسان", 'organization_id' => 1, 'created_by' => 1, 'updated_by'=> 1],
            ['name' => "الباب الذي يدخل منه العروسان", 'organization_id' => 1, 'created_by' => 1, 'updated_by'=> 1],
            ['name' => "وقت دخول اهل العريس", 'organization_id' => 1, 'created_by' => 1, 'updated_by'=> 1],
        ]);

        DB::table('services')->insert([
            ['name' => "زينة القاعة", 'price' => 1000, 'organization_id' => 1,"description" => 'زينة القاعة', 'currency_id' => 1],
            ['name' => "استقبال", 'price' => 1500, 'organization_id' => 1,"description" => 'استقبال', 'currency_id' => 1],
            ['name' => "صالون نسائي", 'price' => 800, 'organization_id' => 1,"description" => 'صالون نسائي', 'currency_id' => 1],
            ['name' => "كيك العرس", 'price' => 500, 'organization_id' => 1,"description" => 'كيك العرس', 'currency_id' => 1],
        ]);
    }
}
