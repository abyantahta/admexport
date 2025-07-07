<?php

namespace App\Exports;

use App\Models\Casemark;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CasemarksSheetExport implements FromCollection, WithHeadings
{
    protected $dn_no, $start_date, $end_date;

    public function __construct($dn_no, $start_date, $end_date)
    {
        $this->dn_no = $dn_no;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Casemark::query();
        if ($this->dn_no) $query->where('dn_no', $this->dn_no);
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Status', 'Casemark No', 'Count Kanban', 'Qty Kanban', 'Part No', 'Part Name', 'Box Type', 'Qty Per Box', 'DN No'
        ];
    }
}
