<?php

namespace App\Http\Controllers;

use App\Models\Dn;
use App\Models\Interlock;
use Illuminate\Http\Request;

class DnController extends Controller
{
    public function index()
    {
        $dateFilter = request('date_filter', '');  // default value, for instance
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::get()->first();
        $dnData = Dn::get();
        // dd($interlock);
        return view('pages.dn', compact('dnData','dateFilter','statusFilter','interlock'));
    }
    //
}
