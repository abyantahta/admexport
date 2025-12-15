<?php

namespace App\Http\Controllers;

use App\Models\Interlock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
// use App\Models\Qcpass;
use App\Models\History;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function printDN(Request $request){
        // User is already authenticated by middleware
        $dn_no = $request->dn_no;
        try {
            if (empty($dn_no)) {
                return response()->json(['message' => 'DN number missing'], 422);
            }

            // Generate PDF with Spatie Laravel PDF
            $filename = 'DN_' . $dn_no . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('DN');

            $pdfPath = storage_path("app/public/DN/{$filename}");

            // Render simple DN PDF
            Browsershot::html(view('pages.print-dn-pdf', compact('dn_no'))->render())
                ->timeout(60000)
                ->paperSize(130, 85, 'mm') // 144x89mm in millimeters
                ->margins(0, 0, 0, 0) // No margins
                ->dismissDialogs() // Dismiss any browser dialogs
                ->waitUntilNetworkIdle() // Wait for network to be idle
                ->emulateMedia('print') // Emulate print media
                ->showBackground() // Show background colors and images
                ->savePdf($pdfPath);

            $exists = file_exists($pdfPath);

            return response()->json([
                'status' => $exists ? 'saved' : 'not_saved',
                'path' => $pdfPath,
            ]);


                // $apiKey = 'um2d2TZoQ9PSALFymVYmHgOqmXVWjCQ-p8exbhUv8Ss';
                // $apiPassword = env('PRINTNODE_PASSWORD', '');
                // $httpClient = Http::withBasicAuth($apiKey, $apiPassword)
                //     ->timeout(60) // total request timeout
                //     ->connectTimeout(10) // fail faster on connection issues
                //     ->retry(3, 500); // simple retry to ride out transient hiccups

                // $response = $httpClient->get('https://api.printnode.com/printers');


                // $pdfBase64 = base64_encode(file_get_contents(storage_path("app/public/labels/label-print.pdf")));


                // $response = $httpClient
                //     ->post('https://api.printnode.com/printjobs', [
                //         'printerId' => $printerId,
                //         'title' => 'Label Print',
                //         'contentType' => 'pdf_base64',
                //         'content' => $pdfBase64,
                //         'source' => 'LaravelApp',
                //         'options' => [
                //             'fit_to_page' => false, // Prevent scaling
                //             // 'paper' => 'Custom.100x89mm', // Custom paper size
                //             'scale' => 100, // 100% scale - no scaling
                //             'auto_rotate' => false, // Prevent auto rotation
                //             'auto_center' => false, // Prevent auto centering
                //         ],
                //     ]);

                // if ($response->successful()) {
                //     return $request->ajax()
                //         ? response()->json(['status' => 'success'])
                //         : redirect()->back()->with('print_status', 'success');
                // }
                // Log::error('PrintNode error response', [
                //     'status' => $response->status(),
                //     'body' => $response->body(),
                // ]);
                // return $request->ajax()
                //     ? response()->json(['message' => 'PrintNode API Error: ' . $response->status()], 500)
                //     : redirect()->back()->with('print_status', 'error');


        } catch (\Exception $e) {
            // Update history record with error status if it exists
            if (isset($historyRecord)) {
                $historyRecord->update([
                    'print_status' => 'error',
                    'error_message' => 'PDF Generation Error: ' . $e->getMessage()
                ]);
            }
            
            Log::error('PDF Generation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return $request->ajax()
                ? response()->json(['message' => 'Error generating PDF: ' . $e->getMessage()], 500)
                : redirect()->back()->withErrors('Error generating PDF: ' . $e->getMessage());
        }
    }
    //
}
