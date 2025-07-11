<?php

namespace App\Imports;

use App\Models\Casemark;
use App\Models\Dn;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DnImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!($row[0]==3002869 && $row[1]=="PT. SANKEI DHARMA INDONESIA")){
            throw new \Exception('Please submit the correct excel file');
        }

        $isExist = Dn::where('dn_no',$row[8])->first();
        if($isExist){
            $isExist->qty_casemark = $isExist->qty_casemark + 1;
            $isExist->save();
        }else{
            $order_date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays(((int)$row[6] - 2));
            // dd($order_date);
            Dn::create([
                'dn_no'=>$row[8],
                'truck_no'=>$row[18],
                'week'=>$row[4],
                'order_date'=>$order_date,
                'cycle'=> $row[5],
                'periode'=>$row[20],
                'etd'=>$row[21],
            ]);
        }
        return new Casemark([
            'casemark_no' => $row[17], 
            'part_no' => $row[9],
            'part_name' => $row[10],
            'box_type' => $row[11],
            'qty_per_box' => $row[12],
            'qty_kanban' => $row[13],
            'count_kanban' => 0,
            'isMatched' => false,
            'dn_no' => $row[8],
        ]);
    }
    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris ke-5
    }
}
