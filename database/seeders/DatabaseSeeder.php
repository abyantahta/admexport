<?php

namespace Database\Seeders;

use App\Models\ActiveDn;
use App\Models\Dn;
use App\Models\Interlock;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        Interlock::factory()->create([
            'isLocked' => false,
        ]);
        // ActiveDn::factory([
        //     'id'=> 1
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220302",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220303",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220304",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220305",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220306",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220307",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220308",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220309",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220310",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220311",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220312",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
        // Dn::factory()->create([
        //     'dn_no'=> "DN47B02506220313",
        //     'cycle'=> 103,
        //     "truck_no"=> "20250622-2-A-KZKRW7-1-01-0",
        //     "week"=> 2,
        //     "order_date"=> Carbon::parse("10-06-2025"),
        //     "periode"=> 202506,
        //     "etd" => 20250622,
        //     "qty_casemark"=> 8,
        //     "count_casemark"=> 0,
        //     // "dn_seq"=>
        // ]);
    }
}
