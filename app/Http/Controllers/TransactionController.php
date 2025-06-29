<?php

namespace App\Http\Controllers;

use App\Models\Interlock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        $dateFilter = request('date_filter', '');
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::latest()->first();
        
        $transactions = Transaction::all();
        // $dnData = Dn::select('dn_no')->distinct()->get();
        // $casemarkData = Casemark::get();
        
        return view('pages.matching', compact('dateFilter', 'statusFilter', 'interlock'));
    }
    public function getTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::select(['barcode_cust', 'no_dn', 'no_job', 'no_seq', 'barcode_fg', 'no_job_fg', 'no_seq_fg', 'status', 'dn_status', 'order_kbn', 'match_kbn','del_cycle','plant', 'created_at'])
            // $transactions = Transaction::select(['barcode_cust', 'no_dn', 'no_job', 'no_seq', 'barcode_fg', 'no_job_fg', 'no_seq_fg', 'status', 'dn_status', 'order_kbn', 'match_kbn','del_cycle', 'created_at'])
            ->latest('created_at'); // Tambahkan ini untuk urutkan dari terbaru;
            // dd($transaction)
            // {{ dd($transaction) }};
            // dd($transactions);
            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('status', function ($transaction) {
                    return '<span class="badge ' . ($transaction->status == 'match' ? 'bg-success' : 'bg-danger') . '">' . ucfirst($transaction->status) . '</span>';
                })
                ->editColumn('dn_status', function ($transaction) {
                    $statusClass = 'bg-danger';
                    $statusText = 'NA';

                    if ($transaction->dn_status == 'open') {
                        $statusClass = 'bg-warning';
                        $statusText = ucfirst($transaction->dn_status);
                    } elseif ($transaction->dn_status == 'close') {
                        $statusClass = 'bg-success';
                        $statusText = ucfirst($transaction->dn_status);
                    }

                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->editColumn('created_at', function ($transaction) {
                    return $transaction->created_at->format('d/m/y - H:i'); // Ubah format tanggal di sini
                })
                ->editColumn('plant', function ($transaction) {
                    $statusClass = 'bg-primary';
                    $statusText = $transaction->plant;

                    if ($transaction->plant == 'ADM KAP') {
                        $statusClass = 'bg-secondary';
                        // $statusText = ucfirst($transaction->plant);
                    } elseif ($transaction->plant == 'ADM KEP') {
                        $statusClass = 'bg-info';
                        // $statusText = ucfirst($transaction->plant);
                    }

                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->rawColumns(['status', 'dn_status', 'created_at','plant']) // Jangan lupa tambahkan 'created_at' di sini
                ->make(true);
                // dd($transaction);
        }
    }
    //
}
