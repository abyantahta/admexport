<?php

namespace App\Exports;

use App\Models\Casemark;
use App\Models\Dn;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CasemarksSheetExport implements FromView, WithHeadings
{
    protected $dn_no, $start_date, $end_date;

    public function __construct($dn_no, $start_date, $end_date)
    {
        $this->dn_no = $dn_no;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $query = Casemark::query();
        if ($this->start_date && $this->end_date) {
            $dnNos = Dn::whereBetween('order_date', [$this->start_date, $this->end_date])
            ->pluck('dn_no');
            $query->whereIn('dn_no', $dnNos);        
        }
        
        if ($this->dn_no) $query->where('dn_no', $this->dn_no);
        $casemarks = $query->get();
        return view('pages.export_casemarks', [
            'casemarks' => $casemarks
        ]);
    }

    public function headings(): array
    {
        return [
            'No', 'Status', 'Casemark No', 'Count Kanban', 'Qty Kanban', 'Part No', 'Part Name', 'Box Type', 'Qty Per Box', 'DN No'
        ];
    }
}
