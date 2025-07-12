<?php

namespace App\Http\Controllers;

use App\Imports\DnImport;
use App\Models\Dn;
use App\Models\Casemark;
use App\Models\Interlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\CasemarksTransactionsExport;
use Carbon\Carbon;

class DnController extends Controller
{
    public function index()
    {
        $dateFilter = request('date_filter', '');
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::latest()->first();
        
        $dnData = Dn::select('dn_no')->distinct()->get();
        $casemarkData = Casemark::get();
        
        return view('pages.dn', compact('dnData', 'casemarkData', 'dateFilter', 'statusFilter', 'interlock'));
    }

    public function getDnData(Request $request)
    {
        $query = Dn::query();
        
        if($request->start_date && $request->end_date){
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        if ($request->dn_no) {
            $query->where('dn_no', $request->dn_no);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('isMatch', function ($transaction) {
                return '<span class="inline-block text-center ' . ($transaction->isMatch? 'bg-green-600 text-white py-1 w-24 rounded-full' : 'bg-red-400 text-white py-1 w-24 rounded-full') . '">' . ($transaction->isMatch? 'Matched' : 'Unmatched') . '</span>';
            })
            ->rawColumns(['isMatch'])
            ->make(true);
    }

    public function getFilteredDnOptions(Request $request)
    {
        $query = Dn::query();
        
        if($request->start_date && $request->end_date){
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        return $query->select('dn_no')->distinct()->get();
    }

    public function importDn(Request $request)
    {
        $request->validate([
            'file' => 'required|max:2048',
        ]);
        try {
            Excel::import(new DnImport, $request->file('file'));
            return back()->with('success', 'DNs imported successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'Duplicate entry: Please try again with different data');
            }
            return back()->with('error', 'SQL Error: ' . $e->getMessage());
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->with('error', 'Wrong format: Please submit with the correct format');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Please submit the correct excel file')) {
                return back()->with('error', 'Please submit the correct excel file');
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function exportTransactions(Request $request)
    {
        $dn_no = $request->query('dn_no');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $fileName = "Transactions.xlsx";
        if($dn_no){
            $fileName = 'Transactions '.$dn_no.'.xlsx';
        }else if($start_date){
            if($end_date){
                $fileName = 'Transactions ['.Carbon::parse($start_date)->format('d M Y').'-'.Carbon::parse($end_date)->format('d M Y').'].xlsx';
            }else{
                $fileName = 'Transactions ['.Carbon::parse($start_date)->format('d M Y').'].xlsx';
            }
        }

        $export = new CasemarksTransactionsExport($dn_no, $start_date, $end_date);

        return Excel::download($export, $fileName);
    }
}
