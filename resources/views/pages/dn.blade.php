<x-layout>
    <div class="w-full h-lvh">
        @if ($interlock->isLocked)
        <div class="bg-[rgba(0,0,0,0.8)] absolute top-0 left-0 w-full h-lvh flex items-center justify-center z-[9999]">
            <div class="w-full mx-4 flex flex-col items-center justify-center bg-white pb-4 rounded-md">  
                <h1 class="text-xl text-center font-bold bg-red-400 w-full h-12 flex items-center justify-center text-white mb-2">This page is Locked</h1>
                <h2 class="text-sm">Mismatch at <b>{{ $interlock->created_at }}</b></h2>
                <h2 class="text-sm">Part PCC : <b>{{ $interlock->part_no_pcc }}</b></h2>
                <h2 class="text-sm">Part FG : <b>{{ $interlock->part_no_fg }}</b></h2>

                <p class="text-center text-sm my-2 font-bold text-red-400">Hubungi Leader untuk Passkey</p>
                <form class="" action="{{ route('matching.unlock') }}" method="POST" class="mt-2">
                    @csrf
                    <div class="md:mb-4 md:mt-3 flex flex-col items-center gap-2 justify-center">
                        {{-- <label for="barcode" class="form-label">SCAN BARCODE</label> --}}
                        {{-- <input type="text" class="form-control" id="barcode" name="barcode" readonly  required autofocus> --}}
                        @if (session('passkey_error'))
                            <div class="bg-red-400 text-white w-full font-bold text-center rounded-md py-1">Passkey salah!</div>
                        @endif
                        <input type="password" placeholder="Unlock Passkey"
                            class="pl-4 w-full border-1 border-solid border-gray-700 form-control h-7 md:h-10 " id="passkey"
                            name="passkey" required autofocus>
                            <button class="bg-green-700 mx-auto px-4 rounded-sm text-white" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    <div class="container mx-auto px-3 sm:px-0 mt-2">
        <div class=" w-full  ">
            {{-- <h3 class="card-header p-3 text-3xl"><i class="fa fa-star"></i> PCC MATCHING</h3> --}}
            <div class=" flex items-center justify-center flex-col mt-7">
                <h2 class="text-2xl font-bold mb-2">Upload DN</h2>

                @session('success')
                    <div class="alert alert-success w-full" role="alert">
                        {!! session('success') !!}
                        @if (session('filename'))
                            <div class="mt-2">
                                <a href="{{ route('pcc.download', session('filename')) }}"
                                    class="text-blue-500 hover:text-blue-700 underline">
                                    Download modified PCC
                                </a>
                            </div>
                        @endif
                    </div>
                @endsession
                @session('error')
                    <div class="alert alert-danger w-full" role="alert">
                        {{ $value }}
                    </div>
                @endsession

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pcc.upload') }}" method="POST" enctype="multipart/form-data" class=" w-full flex flex-col gap-3 items-center justify-content">
                    @csrf
                    {{-- <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="default_size">Default size</label>
                    <input class="block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="default_size" type="file"> --}}
                    <div class="w-full my-2  border-2 rounded-xl border-1 border-blue-600">
                        <form>
                          <label class="block">
                            <span class="sr-only">Choose profile photo</span>
                            <input type="file" class="block w-full text-sm text-gray-500
                              file:me-4 file:py-1 md:file:py-2 file:px-4
                              file:rounded-l-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-600 file:text-white
                              hover:file:bg-blue-700
                              file:disabled:opacity-50 file:disabled:pointer-events-none
                            ">
                          </label>
                        </form>
                      </div>
                    {{-- <input type="file" name="pdf" accept=".pdf" class="form-control w-full rounded-full"> --}}

                    {{-- <br> --}}
                    {{-- <h2 class="w-full bg-purple-400">dnsaodnas</h2> --}}
                    <div class="flex flex-col gap-2 md:flex-row w-full md:mt-4">
                        <button class="bg-yellow-400 rounded-md py-1 font-semibold md:px-8"><i class="fa fa-file"></i> Import DN</button>
                        <a class="bg-green-400 py-1 rounded-md font-bold md:px-8 flex gap-2 items-center justify-center"
                            href="{{ url('export/transactions?date_filter=' . $dateFilter . '$statusFilter=' . $statusFilter) }}"><i
                            class="fa fa-file"></i>Export Transaction</a>
                    </div>

                </form>

            </div>


        </div>
        <div class="flex flex-col mt-4 md:mt-0 md:flex-row justify-end gap-2  md:gap-3 mb-3">
            <div class=" w-full min-w-[200px] md:w-36">
                <div class="relative">
                    <input type="date" value="{{ $dateFilter ?? '' }}" placeholder=" Select Delivery Date"
                        class="tracking-widest w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer"
                        id="filter-date">

                </div>
            </div>
            <div class="w-full min-w-[200px] md:w-44">
                <div class="relative">
                    <select value="{{ $statusFilter ?? '' }}" id="statusFilter"
                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                        <option value="">Select Status</option>
                        <option value='matched'>Matched</option>
                        <option value='unmatched'>Unmatched</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2"
                        stroke="currentColor" class="h-5 w-5 ml-1 absolute top-2.5 right-2.5 text-slate-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </div>
            </div>
            {{-- <input type="date" placeholder="Select Delivery Date" class="ml-auto border-3 text-md tracking-wider border-gray-500 py-2 px-4 rounded-md" id="filter-date"> --}}
        </div>
        <div class=" " style="margin-top:5px;">
            <div class="card-body overflow-x-scroll">
                <table id="dnTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Slip</th>
                            <th>Slip Seq</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>KD Lot No</th>
                        </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- <script>
        $(document).ready(function() {
            // Set focus on the barcode input field when the page loads
            $('#barcode').focus();

            // Reset focus to barcode input field after form submission or other actions
            $('form').on('submit', function() {
                setTimeout(function() {
                    $('#barcode').focus();
                }, 100); // Delay slightly to ensure focus resets
            });
        });
    </script>
    <!-- Tambahkan jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#dnTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pcc.data') }}",
                    data: function(d) {
                        d.created_at = $('#filter-date').val(); // Send selected date to server
                        d.pccStatus = $('#statusFilter').val();
                    }

                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    }, {
                        data: 'isMatch',
                        name: 'isMatch'
                    },
                    {
                        data: 'slip_barcode',
                        name: 'slip_barcode'
                    },
                    {
                        data: 'pcc_count',
                        name: 'pcc_count'
                    },
                    {
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'kd_lot_no',
                        name: 'kd_lot_no'
                    },
                ]
            });
            $('#filter-date, #statusFilter').change(function() {
                table.draw(); // Reload table when date changes
            });
            document.querySelector('.exportButton').addEventListener('click', function(e) {
                // Update hidden input values with current filter values
                var dateFilter = $('#filter-date').val()
                var statusFilter = $('#statusFilter').val()
                this.href = "{{ url('/export/transactions') }}" + "?date_filter=" + dateFilter +
                    "&status_filter=" + statusFilter;
            });

            // Setup - add a text input to each footer cell
            $('#demoTable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });
    </script> --}}
    </div>
</x-layout>