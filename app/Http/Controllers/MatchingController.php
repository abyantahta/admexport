<?php

namespace App\Http\Controllers;

use App\Models\ActiveDn;
use App\Models\Casemark;
use App\Models\Dn;
use App\Models\Interlock;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MatchingController extends Controller
{
    public function index()
    {
        $dateFilter = request('date_filter', '');
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::latest()->first();

        $transactions = Transaction::all();
        $tempActiveDn = ActiveDn::query()->first();
        $activeDn = [
            'dn_no' => "",
            'qty_casemark' => "",
            'count_casemark' => ""
        ];
        $activeCasemark = [
            'casemark_no' => "",
            'qty_kanban' => "",
            'count_kanban' => ""
        ];
        if (isset($tempActiveDn->dn_no)) {
            $dn = Dn::query()->where('dn_no', $tempActiveDn->dn_no)->first();
            $activeDn['dn_no'] = $dn->dn_no;
            $activeDn['qty_casemark'] = $dn->qty_casemark;
            $activeDn['count_casemark'] = $dn->count_casemark;

            if ($tempActiveDn->casemark_no) {
                $casemark = Casemark::query()->where('casemark_no', $tempActiveDn->casemark_no)->first();
                $activeCasemark['casemark_no'] = $casemark->casemark_no;
                $activeCasemark['qty_kanban'] = $casemark->qty_kanban;
                $activeCasemark['count_kanban'] = $casemark->count_kanban;
            }
        }
        return view('pages.matching', compact('dateFilter', 'statusFilter', 'interlock', 'activeDn', 'activeCasemark'));
    }
    public function getTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::query()
                ->latest('created_at');

            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('created_at', function ($transaction) {
                    return Carbon::parse($transaction->created_at)->format('d/m/Y - H:m');
                })
                ->editColumn('status', function ($transaction) {
                    return '<span class="badge py-1 px-4 rounded-full ' . ($transaction->status == 'match' ? 'bg-green-600 text-white' : 'bg-red-600 text-white') . '">' . ucfirst($transaction->status) . '</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }
    public function store(Request $request)
    {
        $input = trim($request->input('barcode')); // trim untuk menghilangkan whitespaces
        // dd($input);

        $activeDn = (ActiveDn::query()->first());
        // $is_dn_active = false;
        $is_casemark_active = false;

        //CEK APAKAH SUDAH ADA ACTIVE DN (NGELOCK)
        if (isset($activeDn)) {
            $is_casemark_active = isset($activeDn->casemark_no);
            
            //CEK APAKAH CASEMARK NYA SUDAH NGELOCK
            if (!$is_casemark_active) {
                if (strlen($input) == 28 && (preg_match('/^[A-Z]\d{2}-SDI-\d{5}-\d{2}##\d{8}#\d$/', $input) || preg_match('/^[A-Z]{2}\d{1}-SDI-\d{5}-\d{2}##\d{8}#\d$/', $input))) {
                    $casemark_from_input = substr($input, 0, 13);
                    // dd($casemark_from_input);
                    $thisCasemark = Casemark::where('casemark_no', $casemark_from_input)->first();
                    //CEK APAKAH CASEMARK SESUAI FORMAT PENULISAN DAN SUDAH TERDAFTAR DI DATABASE
                    if ($thisCasemark) {
                        // dd($thisCasemark,$activeDn);
                        $isThisCasemarkValid = ($thisCasemark->dn_no) == ($activeDn->dn_no);
                        //CEK APAKAH FORMAT CASEMARK YANG DISCAN TERDAFTAR PADA DN YANG DISCAN SEBELUMNYA
                        if (!$isThisCasemarkValid) {
                            //CASEMARK TIDAK SESUAI DENGAN DN YANG DISCAN
                            return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>SCAN ULANG</b></span>, ' . $input . '(L:' . strlen($input) . ') Casemark tidak sesuai dengan DN, scan Casemark yang sesuai');
                        }

                        if ($thisCasemark->isMatched) {
                            return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Casemark Close, sudah dimatch sebelumnya');
                        }
                        //KALAU COCOK, SIMPAN
                        $activeDn->update([
                            'casemark_no' => $casemark_from_input,
                        ]);
                        //BERHASIL
                        return redirect()->back()->with('message-match', 'SILAHKAN SCAN BARCODE KANBAN');
                    }
                    return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>SCAN ULANG</b></span>, ' . $input . '(L:' . strlen($input) . ') Casemark tidak ditemukan dalam database');
                }
                return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>SCAN ULANG</b></span>, ' . $input . '(L:' . strlen($input) . ') Masukkan format QR Casemark yang benar');

                //CASEMARK TIDAK DITEMUKAN ATAU FORMATNYA SALAH
            }
            //SCAN KANBAN
            else {
                //KANBAN SESUAI FORMAT DAN SESUAI DENGAN CASEMARK
                if (strlen($input) == 63 && str_starts_with($input, $activeDn->casemark_no)) {

                    $input_parts = explode('#', $input);

                    $kanban_part_no = $input_parts[1];
                    $kanban_seq = substr($input_parts[3], 0, 3);

                    $isTransactionDouble = Transaction::query()->where('kanban_barcode', $input_parts[0] . $kanban_seq)->exists();
                    if ($isTransactionDouble) {
                        return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Kanban sudah pernah discan sebelumnya!');
                    }
                    // dd($input_parts);
                    session()->put('part_no_kanban', $kanban_part_no);
                    session()->put('seq_kanban', $kanban_seq);
                    Session::put('temp_data', [
                        'casemark_no' => $input_parts[0],
                        'part_no_kanban' => $kanban_part_no,
                        'kanban_seq' => $kanban_seq,
                    ]);

                    return redirect()->back()->with('message-match', 'SILAHKAN SCAN LABEL OK QC');
                    //KANBAN SESUAI FORMAT TAPI TIDAK SESUAI DENGAN CASEMARK YANG DISCAN SEBELUMNYA
                } else if (strlen($input) == 63) {
                    return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>SCAN ULANG</b></span> ' . $input . '(L:' . strlen($input) . ') Kanban tidak sesuai dengan casemark');
                    //SCAN LABEL OK 
                } else if (strlen($input) == 28 && (preg_match('/^\d{5}-[A-Z]{2}\d{3}-\d{2}-[A-Z]{2}\d{3}#[A-Z0-9]{7}$/', $input))) {
                    $tempData = Session::get('temp_data');
                    if (!$tempData) {
                        return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>SCAN ULANG</b></span>, ' . $input . '(L:' . strlen($input) . ') Casemark tidak ditemukan dalam database');
                    }
                    $label_part_no = substr($input, 0, 17);
                    $label_seq = substr($input, 17, 3);
                    $lot_no = substr($input, 21, 7);
                    session()->put('part_no_label', $label_part_no);
                    session()->put('seq_label', $label_seq);
                    // dd($label_part_no,$label_seq,$lot_no);
                    if ($label_part_no == $tempData['part_no_kanban']) {

                        $isLabelDuplicate = Transaction::query()->where('seq_no_label',$label_seq)->where('lot_no',$lot_no)->exists();
                        if($isLabelDuplicate){
                            return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Label dengan Sequence : '.$label_seq." dan Lot : ".$lot_no." sudah pernah discan!");
                        }

                        $casemark = Casemark::query()->where('casemark_no', $activeDn->casemark_no)->first();
                        $dn = Dn::query()->where('dn_no', $activeDn->dn_no)->first();

                        $casemark->update([
                            'count_kanban' => $casemark->count_kanban + 1
                        ]);
                        // dd($tempData);
                        Transaction::create([
                            'kanban_barcode' => $tempData['casemark_no'] . $tempData['kanban_seq'],
                            'part_no_kanban' => $tempData['part_no_kanban'],
                            'seq_no_kanban' => $tempData['kanban_seq'],
                            'part_no_label' => $label_part_no,
                            'seq_no_label' => $label_seq,
                            'lot_no' => $lot_no,
                            'label_barcode' => $label_part_no . $label_seq,
                            'status' => 'match',
                            'casemark_no' => $activeDn['casemark_no'],
                            'dn_no' => $activeDn['dn_no']
                        ]);
                        session()->flush();

                        if ($casemark->count_kanban == $casemark->qty_kanban) {
                            $casemark->update([
                                'isMatched' => true
                            ]);
                            $activeDn->update([
                                'casemark_no' => null
                            ]);
                            $dn->update([
                                'count_casemark' => $dn->count_casemark + 1
                            ]);
                            if ($dn->qty_casemark == $dn->count_casemark) {
                                $dn->update([
                                    'isMatch' => true
                                ]);
                                $activeDn->delete();
                                return redirect()->back()->with('message-match', 'Kanban ' . $activeDn->casemark_no . $tempData['kanban_seq'] . " berhasil match. DN " . $dn->dn_no . " sudah closed, siap dikirim!");
                            }
                            return redirect()->back()->with('message-match', 'Kanban ' . $activeDn->casemark_no . $tempData['kanban_seq'] . " berhasil match. Casemark " . $casemark->casemark_no . "  sudah close, Silahkan SCAN Casemark yang lain");
                        }


                        return redirect()->back()->with('message-match', 'Kanban ' . $activeDn->casemark_no . $tempData['kanban_seq'] . " berhasil match");
                    } else {
                        Transaction::create([
                            'kanban_barcode' => $tempData['casemark_no'] . $tempData['kanban_seq'],
                            'part_no_kanban' => $tempData['part_no_kanban'],
                            'seq_no_kanban' => $tempData['kanban_seq'],
                            'part_no_label' => $label_part_no,
                            'seq_no_label' => $label_seq,
                            'lot_no' => $lot_no,
                            'label_barcode' => $label_part_no . $label_seq,
                            'status' => 'mismatch',
                            'casemark_no' => $activeDn['casemark_no'],
                            'dn_no' => $activeDn['dn_no']
                        ]);

                        Interlock::query()->create([
                            'isLocked' => true,
                            'created_at' => Carbon::now(),
                            'part_no_kanban' => $tempData['part_no_kanban'],
                            'part_no_fg' => $label_part_no
                        ]);
                        try {
                            $response = Http::withHeaders([
                                'Authorization' => 'DcjkiWJ9gwbp7scYKowe',
                            ])->withOptions(['verify' => false])->post('https://api.fonnte.com/send', [
                                'target' => '089522134460, 081270074197,082245792234',
                                'message' => 'Terjadi mismatch pengiriman ADM Export pukul ' . Carbon::now()->format('H:i') . '
                                                Segera datang ke line.',
                                'delay' => '2'
                            ]);
                        } catch (\Exception $e) {
                        }
                        session()->forget('part_no_label');
                        session()->forget('seq_label');
                        return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>MISMATCH</b></span>, ' . $input . '(L:' . strlen($input) . ') Kanban tidak sesuai!');
                    }

                    //TIDAK SESUAI FORMAT
                } else {
                    $tempData = Session::get('temp_date');
                    if ($tempData) {
                        return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Label tidak sesuai format, SCAN ULANG !');
                    }
                    return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>FORMAT TIDAK SESUAI</b></span>, ' . $input . '(L:' . strlen($input) . ') Kanban tidak sesuai format, SCAN ULANG !');
                }
            }
            // if($is)
        } else {
            if ((str_starts_with($input, 'DN') || str_starts_with($input, 'SO')) && strlen($input) == 16) {
                $thisDn = Dn::where('dn_no', $input)->first();

                if (!isset($thisDn)) {
                    //BELUM ADA DN TERUPLOAD
                    return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') DN belum terupload');
                }
                if ($thisDn->isMatch) {
                    return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') DN Close, sudah dimatch sebelumnya');
                }
                ActiveDn::create([
                    'dn_no' => $input
                ]);
                $detailActiveDn = Dn::where('dn_no', $input)->first();
                // Session::put('no_dn', $input);

                //BERHASIL, SCANLAH CASEMARK
                return redirect()->back()->with('message-match', 'SILAHKAN SCAN BARCODE CASEMARK');
            }
            //HARUS SCAN FORMAT DN TERLEBIH DAHULU
            return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Silahkan scan DN terlebih dahulu ');
        }




























        // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
        // if (str_starts_with($input, 'DN') || str_starts_with($input, 'SO')) {
        //     // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
        //     if (strlen($input) === 26) {
        //         $no_dn =  substr($input, 0, -10);     // Extract "DNxxxxxxxxxxxxxAAA"
        //         $no_job = substr($input, -10, 7);     // Extract "AAA-yyyy"
        //         $no_seq = substr($input, -3);        // Extract "zzz"
        //     } else if (strlen($input) === 31) { // DN21241000527217400-BZ040-00001
        //         $no_dn =  substr($input, 0, -17);     // Extract "DNxxxxxxxxxxxxxAAA"
        //         $no_job = substr($input, -17, 14);     // Extract "AAA-yyyy"
        //         $no_seq = substr($input, -3);        // Extract "zzz"
        //         // dd($no_dn,$no_job,$no_seq);
        //     } else {
        //         // Handle invalid input formats for both data1 and data2
        //         return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
        //     }

        //     // Cek apakah barcode_cust sudah ada di database
        //     $existingTransaction = Transaction::where('barcode_cust', $input)->where('status', "match")->latest()->first();

        //     if ($existingTransaction) {
        //         return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Barcode customer  sudah pernah berhasil di matching.');
        //     }
        //     // dd($no_dn,$no_job);
        //     // $existingDn = (strlen($input) === 26) ? DnADM::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first()
        //     // : DnADMKEP::where('dn_no', $no_dn)->where('part_no', $no_job)->latest()->first();
        //     $existingDn = DnADM::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first();
        //     if(!$existingDn && strlen($input)===26){
        //         $existingDn = DnADMKAP::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first();
        //     }else if(!$existingDn){
        //         $existingDn = DnADMKEP::where('dn_no', $no_dn)->where('part_no', $no_job)->latest()->first();
        //     }
        //     // dd($existingDn);
        //     $last_match = Transaction::where('no_dn', $no_dn)->where('no_job', $no_job)->latest()->first();
        //     // dd($existingDn);
        //     if ($existingDn) {
        //         $order_kbn = (strlen($input) === 26)?  $existingDn->order_kbn : $existingDn->qty_kanban;
        //         $plant = "ADM SAP";
        //         if($existingDn->plant_code==="D102") $plant="ADM KEP";
        //         if($existingDn->plant_code==="D105") $plant="ADM KAP";
        //         $del_cycle = $existingDn->del_cycle;

        //         if ($last_match) {
        //             $dn_status = $last_match->dn_status;
        //                 if($dn_status == 'close'){
        //                     return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') DN Status Close, Seluruh kanban dalam DN sudah berhasil match');
        //                 }
        //             // dd($last_match);
        //             $match_kbn = $last_match->match_kbn;
        //             // dd($match_kbn);

        //         } else {
        //             $match_kbn = 0;
        //             $dn_status = "open";
        //         }

        //         // dd($order_kbn);
        //     } else {
        //         return redirect()->back()->withErrors('<span class="badge bg-warning" > <b>NO DATA</b> </span>, ' . $no_dn . ' Belum ada DN yang di upload di sistem.');
        //     }
        //     // Store data1 temporarily in session and prepare for data2
        //     Session::put('temp_data', [
        //         'barcode_cust' => $input,
        //         'no_dn' => $no_dn,
        //         'no_job' => $no_job,
        //         'no_seq' => $no_seq,
        //         'order_kbn' => $order_kbn,
        //         'match_kbn' => $match_kbn,
        //         'del_cycle' => $del_cycle,
        //         'dn_status' => $dn_status,
        //         'plant' => $plant,
        //         'status' => 'pairing',
        //         'created_at' => now()
        //     ]);

        //     // Store no_dn and no_job in persistent session variables
        //     Session::put('barcode_cust', $input);
        //     Session::put('no_dn', $no_dn);
        //     Session::put('no_job', $no_job);
        //     Session::put('no_seq', $no_seq);
        //     Session::put('order_kbn', $order_kbn);
        //     Session::put('match_kbn', $match_kbn);
        //     Session::put('del_cycle', $del_cycle);
        //     Session::put('plant', $plant);
        //     Session::put('dn_status', $dn_status);

        //     return redirect()->back()->with('message', $input . '(L:' . strlen($input) . ') SILAHKAN SCAN BARCODE FG.');
        // }

        // Check if input is Data2 with the updated format "BX-yyyy-zzz"
        // if (preg_match('/-[\d]{3}$/', $input)| preg_match('/-[\d]{5}$/', $input))  {

        //     $tempData = Session::get('temp_data');

        //     if (!$tempData) {
        //         return redirect()->back()->withErrors('SCAN BARCODE CUSTOMER SEBELUM BARCODE FG.');
        //     }
        //     // if (strlen($input) === 11) {
        //     // if(strlen($input===11))?
        //     $no_job_fg = (strlen($input)===11)? substr($input, 0, -4) : substr($input, 0, -3);   // Extract "BX-yyyy"
        //     $no_seq_fg = substr($input, -3);     // Extract "zzz"
        //     // }
        //     // dd($no_job_fg);
        //     Session::flash('barcode_fg', $input);
        //     Session::flash('no_job_fg', $no_job_fg);
        //     Session::flash('no_seq_fg', $no_seq_fg);
        //     // dd($tempData);
        //     // Compare no_job from Data1 and no_job_fg from Data2
        //     if ($tempData['no_job'] !== $no_job_fg) {
        //         Session::flash('no_job_fg', $no_job_fg);

        //         // Kembalikan dua pesan alert sekaligus
        //         // Save the matched transaction if the job numbers mismatch
        //         $transaction = new Transaction();
        //         $transaction->barcode_cust = $tempData['barcode_cust'];
        //         $transaction->no_dn = $tempData['no_dn'];
        //         $transaction->no_job = $tempData['no_job'];
        //         $transaction->no_seq = $tempData['no_seq'];
        //         $transaction->barcode_fg = $input;
        //         $transaction->no_job_fg = $no_job_fg;
        //         $transaction->no_seq_fg = $no_seq_fg;
        //         $transaction->status = 'mismatch';
        //         $transaction->dn_status = $tempData['dn_status'];
        //         $transaction->order_kbn = $tempData['order_kbn'];
        //         $transaction->match_kbn = $tempData['match_kbn'];
        //         $transaction->del_cycle = $tempData['del_cycle'];
        //         $transaction->plant = $tempData['plant'];
        //         // dd($transaction->plant);
        //         $transaction->created_at = now();
        //         $transaction->save();
        //         // dd($transaction);
        //         return redirect()->back()
        //             ->with('message-no-match', '<b>' . $no_job_fg . '</b>, TIDAK SESUAI. SCAN ULANG !')
        //             ->with('message', 'SILAHKAN SCAN KEMBALI BARCODE FG.');
        //     }
        //     // dd($tempData['match_kbn']);
        //     $match_kbn = $tempData['match_kbn'] + 1;
        //     if ($match_kbn >= $tempData['order_kbn']) {
        //         $dn_status = "close";
        //     } else {
        //         $dn_status = $tempData['dn_status'];
        //     }
        //     // Save the matched transaction if the job numbers match
        //     $transaction = new Transaction();
        //     $transaction->barcode_cust = $tempData['barcode_cust'];
        //     $transaction->no_dn = $tempData['no_dn'];
        //     $transaction->no_job = $tempData['no_job'];
        //     $transaction->no_seq = $tempData['no_seq'];
        //     $transaction->barcode_fg = $input;
        //     $transaction->no_job_fg = $no_job_fg;
        //     $transaction->no_seq_fg = $no_seq_fg;
        //     $transaction->status = 'match';
        //     $transaction->dn_status = $dn_status;
        //     $transaction->order_kbn = $tempData['order_kbn'];
        //     $transaction->match_kbn = $match_kbn;
        //     $transaction->del_cycle = $tempData['del_cycle'];
        //     // $transaction->plant = (strlen($tempData['no_dn'])===16)? 'ADM SAP':'ADM KEP';
        //     $transaction->plant = $tempData['plant'];
        //     $transaction->created_at = now();
        //     $transaction->save();

        //     // membuat data Dashboard
        //     if($dn_status=='close'){
        //         Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
        //             "kanban_match"=> $match_kbn."/".$tempData['order_kbn'],
        //             "dn_status"=> $dn_status
        //         ]);
        //     }else{
        //         Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
        //             "kanban_match"=> $match_kbn."/".$tempData['order_kbn'],
        //         ]);
        //     }
        //     // $dataDashboard->cycle = $tempData['del_cycle'];
        //     // $dataDashboard->dn_number = $tempData['del_cycle'];
        //     // Clear temporary session data for data1
        //     Session::forget('temp_data');

        //     // Call the resetSession function to clear session data
        //     $this->resetSession();

        //     return redirect()->back()->with('message-match', 'MATCH,<br>DN: <b>' . $tempData['no_dn'] . '</b>, JOB: <b>' . $tempData['no_job'] . '</b>, SEQ: <b>' . $tempData['no_seq'] . '</b><br> TRANSAKSI BERHASIL DI SIMPAN. ');
        // }

        // Handle invalid input formats for both data1 and data2
        return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
    }
    public function unlock(Request $request)
    {
        $passkey = trim($request->input('passkey')); // trim untuk menghilangkan whitespaces
        // dd($passkey);
        if ($passkey !== "SaNkEi2011..!") {
            return redirect()->back()->with('passkey_error', 'Passkey salah!');
        }
        $interlock = Interlock::query()->latest()->first();
        $interlock->update([
            'isLocked' => false,
            'waiting_time' => abs(Carbon::now()->diffInSeconds($interlock->created_at)),
            'notification_30m_sent' => false,
            'notification_60m_sent' => false,
            'notification_30m_sent_at' => null,
            'notification_60m_sent_at' => null
        ]);
        return redirect()->back();
    }

    public function resetSession()
    {
        // Hapus data no_dn dan no_job dari session
        session()->flush();
        $activeDn = ActiveDn::query()->latest()->first();
        $activeDn->update([
            'casemark_no' => null,
            'dn_no' => null,
        ]);

        // Redirect kembali ke halaman input dengan pesan
        return redirect()->back()->with('message-reset', 'Session has been reset.');
    }

    public function resetSessionWithPassword(Request $request)
    {
        $password = $request->input('reset_password');
        if ($password !== 'SaNkEi2011..!') {
            return redirect()->back()->with('reset_error', 'Password salah!');
        }
        // Hapus data no_dn dan no_job dari session
        session()->flush();
        $activeDn = ActiveDn::query()->latest()->first();
        if ($activeDn) {
            $activeDn->delete();
        }
        // Redirect kembali ke halaman input dengan pesan
        return redirect()->back()->with('message-reset', 'Session has been reset.');
    }
}
