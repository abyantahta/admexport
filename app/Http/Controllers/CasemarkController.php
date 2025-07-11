<?php

namespace App\Http\Controllers;

use App\Models\Casemark;
use App\Models\Dn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class CasemarkController extends Controller
{
    public function getCasemarkData(Request $request)
    {
        $query = Casemark::query();
        
        if($request->start_date && $request->end_date){
            $dnNos = Dn::whereBetween('order_date', [$request->start_date, $request->end_date])
                ->pluck('dn_no');
            $query->whereIn('dn_no', $dnNos);
        }

        // Only apply DN filter if a DN is actually selected
        if ($request->dn_no && $request->dn_no !== '') {
            $query->where('dn_no', $request->dn_no);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('isMatched', function ($transaction) {
                return '<span class="inline-block text-center ' . ($transaction->isMatched? 'bg-green-600 py-1 w-24 text-white rounded-full' : 'bg-red-400 text-white py-1 px-2 rounded-full') . '">' . ($transaction->isMatched? 'Matched' : 'Unmatched') . '</span>';
            })
            ->rawColumns(['isMatched'])
            ->make(true);
    }

    public function getFilteredCasemarkOptions(Request $request)
    {
        $query = Casemark::query();
        
        if($request->start_date && $request->end_date){
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $casemarkOptions = $query->select('casemark_no')->distinct()->get();
        
        return response()->json($casemarkOptions);
    }
} 