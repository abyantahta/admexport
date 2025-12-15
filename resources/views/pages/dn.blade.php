<x-layout>
    <div class="w-full h-lvh">
        @if ($interlock && $interlock->isLocked)
            <div
                class="bg-[rgba(0,0,0,0.8)] absolute top-0 left-0 w-full h-lvh flex items-center justify-center z-[9999]">
                <div class="w-full mx-4 flex flex-col items-center justify-center bg-white pb-4 rounded-md">
                    <h1
                        class="text-xl text-center font-bold bg-red-400 w-full h-12 flex items-center justify-center text-white mb-2">
                        This page is Locked</h1>
                    <h2 class="text-sm">Mismatch at <b>{{ $interlock->created_at }}</b></h2>
                    <h2 class="text-sm">Part Kanban : <b>{{ $interlock->part_no_kanban }}</b></h2>
                    <h2 class="text-sm">Part FG : <b>{{ $interlock->part_no_fg }}</b></h2>

                    <p class="text-center text-sm my-2 font-bold text-red-400">Hubungi Leader untuk Passkey</p>
                    <form class="" action="{{ route('matching.unlock') }}" method="POST" class="mt-2">
                        @csrf
                        <div class="md:mb-4 md:mt-3 flex flex-col items-center gap-2 justify-center">
                            {{-- <label for="barcode" class="form-label">SCAN BARCODE</label> --}}
                            {{-- <input type="text" class="form-control" id="barcode" name="barcode" readonly  required autofocus> --}}
                            @if (session('passkey_error'))
                                <div class="bg-red-400 text-white w-full font-bold text-center rounded-md py-1">Passkey
                                    salah!</div>
                            @endif
                            <input type="password" placeholder="Unlock Passkey"
                                class="pl-4 w-full border-1 border-solid border-gray-700 form-control h-7 md:h-10 "
                                id="passkey" name="passkey" required autofocus>
                            <button class="bg-green-700 mx-auto px-4 rounded-sm text-white"
                                type="submit">Submit</button>
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
                        <div class="bg-green-600 md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm"
                            role="alert">
                            <x-heroicon-s-check-circle class="w-6" />
                            {!! session('success') !!}
                        </div>
                    @endsession
                    @session('error')
                        <div class="bg-red-500 md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm"
                            role="alert">
                            {{-- <x-radix-cross-circled class="w-6" /> --}}
                            {{-- <x-zondicon-close-outline /> --}}
                            <x-codicon-error class="w-8" />
                            {{ $value }}
                        </div>
                    @endsession

                    @if ($errors->any())
                        <div class="bg-red-500 md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm"
                            role="alert">
                            {{-- <x-zondicon-close-outline /> --}}
                            <x-codicon-error class="w-8" />
                            {{-- <p><strong>Whoops!</strong> There were some problems with your input.</p><br><br> --}}
                            {{-- {{ $value }} --}}
                            {!! $errors->first() !!}
                        </div>
                    @endif

                    <form action="{{ route('dn.import') }}" method="POST" enctype="multipart/form-data"
                        class=" w-full flex flex-col gap-3 items-center justify-content">
                        @csrf
                        <div class="w-full my-2  border-2 rounded-xl border-1 border-blue-600">
                            {{-- <form> --}}
                            <label class="block">
                                <span class="sr-only">Choose profile photo</span>
                                <input type="file" name="file"
                                    class="block w-full text-sm text-gray-500
                                file:me-4 file:py-1 md:file:py-2 file:px-4
                                file:rounded-l-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                file:disabled:opacity-50 file:disabled:pointer-events-none
                            ">
                            </label>
                            {{-- </form> --}}
                        </div>
                        <div class="flex flex-col gap-2 md:flex-row w-full md:mt-4">
                            <button type="submit" class="bg-yellow-500 rounded-md py-1 font-semibold md:px-8"><i
                                    class="fa fa-file"></i> Import DN</button>
                            <a class="bg-green-700 text-white py-1 rounded-md font-bold md:px-8 flex gap-2 items-center justify-center"
                                id="exportBtn" href="#">
                                <i class="fa fa-file"></i>Export Transaction
                            </a>
                        </div>
                    </form>
                </div>

            </div>
            <div class=" flex flex-col mt-4 md:mt-4 md:flex-row justify-between gap-2  md:gap-3 mb-3">
                <div class="flex items-center gap-2 order-first  w-fit md:w-full mx-auto">
                    <span class="text-sm font-medium text-gray-700">DN</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="tableToggle" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Casemark</span>
                </div>
                <div class="flex gap-4 md:flex-row flex-col">
                    <div class="w-full min-w-[200px] md:w-72" id="dateRangeContainer">
                        <div class="relative">
                            <input type="text" id="date-range" placeholder="Select Date Range"
                                class="tracking-widest w-full bg-white placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full min-w-[200px] md:w-[13.4em]">
                        <div class="relative">
                            <select value="{{ $statusFilter ?? '' }}" id="statusFilter"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                <option value="">Select DN</option>
                                @foreach ($dnData as $singleDn)
                                    <option value={{ $singleDn->dn_no }}>{{ $singleDn->dn_no }}</option>
                                @endforeach
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor"
                                class="h-5 w-5 ml-1 absolute top-2.5 right-2.5 text-slate-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" " style="margin-top:5px;">
                <div class="card-body overflow-x-auto">
                    <div id="dnTableContainer">
                        <table id="dnTable"
                            class="mt-3 w-full text-center text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            <thead class=" text-xs text-black uppercase bg-blue-600  dark:text-gray-400">
                                <tr class="">
                                    <th scope="col" class="px-6 py-4 text-white">No</th>
                                    <th scope="col" class="px-6 py-4 text-white">Print</th>
                                    <th scope="col" class="px-6 py-4 text-white">Status</th>
                                    <th scope="col" class="px-6 py-4 text-white">Dn Number</th>
                                    <th scope="col" class="px-6 py-4 text-white">Count Casemark</th>
                                    <th scope="col" class="px-6 py-4 text-white">Qty Casemark</th>
                                    <th scope="col" class="px-6 py-4 text-white">Cycle</th>
                                    <th scope="col" class="px-6 py-4 text-white">Truck No</th>
                                    <th scope="col" class="px-6 py-4 text-white">Week</th>
                                    <th scope="col" class="px-6 py-4 text-white">Order Date</th>
                                    <th scope="col" class="px-6 py-4 text-white">Periode</th>
                                    <th scope="col" class="px-6 py-4 text-white">etd</th>
                                    {{-- <th scope="col" class="px-6 py-4 text-white">isMatch</th> --}}
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 font-semibold -pt-3">
                            </tbody>
                        </table>
                    </div>
                    <div id="casemarkTableContainer" style="display: none;">
                        <table id="casemarkTable"
                            class="mt-3 w-full text-center text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            <thead class=" text-xs text-black uppercase bg-blue-600 dark:text-gray-400">
                                <tr class="">
                                    <th scope="col" class="px-6 py-4 text-white">No</th>
                                    <th scope="col" class="px-6 py-4 text-white">Status</th>
                                    <th scope="col" class="px-6 py-4 text-white">Casemark No</th>
                                    <th scope="col" class="px-6 py-4 text-white">Count Kanban</th>
                                    <th scope="col" class="px-6 py-4 text-white">Qty Kanban</th>
                                    <th scope="col" class="px-6 py-4 text-white">Part No</th>
                                    <th scope="col" class="px-6 py-4 text-white">Part Name</th>
                                    <th scope="col" class="px-6 py-4 text-white">Box Type</th>
                                    <th scope="col" class="px-6 py-4 text-white">Count Box</th>
                                    <th scope="col" class="px-6 py-4 text-white">DN No</th>
                                    {{-- <th scope="col" class="px-6 py-4 text-white">isMatched</th> --}}
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 font-semibold -pt-3">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add jQuery and DataTables JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <!-- Add Flatpickr CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
            $(document).ready(function() {
                // Set focus on the barcode input field when the page loads
                $('#barcode').focus();

                // Reset focus to barcode input field after form submission or other actions
                $('form').on('submit', function() {
                    setTimeout(function() {
                        $('#barcode').focus();
                    }, 100); // Delay slightly to ensure focus resets
                });

                // Initialize Flatpickr
                flatpickr("#date-range", {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    placeholder: "Select Date Range",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length === 2) {
                            updateOptions();
                            if ($('#tableToggle').is(':checked')) {
                                casemarkTable.ajax.reload();
                            } else {
                                dnTable.ajax.reload();
                            }
                        }
                    }
                });

                // Function to update options based on selected table
                function updateOptions() {
                    const dateRange = $('#date-range').val();
                    let url = "{{ route('dn.filtered-options') }}";

                    if (dateRange) {
                        const dates = dateRange.split(' to ');
                        url += `?start_date=${dates[0]}&end_date=${dates[1] || dates[0]}`;
                    }

                    $.get(url, function(data) {
                        const select = $('#statusFilter');
                        const currentValue = select.val(); // Store current value
                        select.empty();
                        select.append('<option value="">Select DN</option>');

                        data.forEach(function(item) {
                            select.append(`<option value="${item.dn_no}">${item.dn_no}</option>`);
                        });

                        // Restore previous value if it exists in new options
                        if (currentValue) {
                            select.val(currentValue);
                        }
                    });
                }

                // Initialize DN DataTable
                var dnTable = $('#dnTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    autoWidth: false,
                    pageLength: 10,
                    dom: '<"flex flex-col-reverse gap-y-2 md:flex-row md:justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    ajax: {
                        url: "{{ route('dn.data') }}",
                        type: 'GET',
                        data: function(d) {
                            const dateRange = $('#date-range').val();
                            if (dateRange) {
                                const dates = dateRange.split(' to ');
                                d.start_date = dates[0];
                                d.end_date = dates[1] || dates[0];
                            }
                            d.dn_no = $('#statusFilter').val();
                        }
                    },
                    columns: [{
                            data: null,
                            width: '5%',
                            className: 'px-6 py-3',
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + 1 + meta.settings._iDisplayStart;
                            }
                        },
                        {
                            data: null,
                            name: 'print',
                            width: '7%',
                            className: 'px-6 py-3 text-center',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                console.log(row);
                                const matched = row.count_casemark==row.qty_casemark;
                                const btnClass = matched ? 'bg-green-600 hover:bg-green-700 cursor-pointer' : 'bg-gray-300';
                                const title = matched ? 'Matched - print label' : 'Unmatched - print label';
                                const disabled = matched? "" : "disabled"
                                // console.log(disabled);
                                return `<button type="button"
                                            class="print-label-btn ${btnClass} text-white px-3 py-1 rounded flex items-center gap-2 mx-auto"
                                            data-dn="${row.dn_no}"
                                            data-matched="${matched}"
                                            title="${title}"
                                            ${disabled}
                                            >
                                            Print
                                        </button>`;
                            }
                        },
                        {
                            data: 'isMatch',
                            name: 'isMatch',
                            width: '5%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'dn_no',
                            name: 'dn_no',
                            width: '25%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'count_casemark',
                            name: 'count_casemark',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'qty_casemark',
                            name: 'qty_casemark',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'cycle',
                            name: 'cycle',
                            width: '25%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'truck_no',
                            name: 'truck_no',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'week',
                            name: 'week',
                            width: '5%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'order_date',
                            name: 'order_date',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'periode',
                            name: 'periode',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'etd',
                            name: 'etd',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                    ],
                    language: {
                        emptyTable: "No data available",
                        zeroRecords: "No matching records found",
                        lengthMenu: "Show _MENU_ entries",
                        search: "",
                        searchPlaceholder: "Search DN..."
                    },
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hover:bg-gray-50');
                    },
                    drawCallback: function() {
                        $('.paginate_button').addClass(
                            'cursor-pointer px-3 py-1 bg-white border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200'
                            );
                        $('.paginate_button.current').addClass('bg-blue-500 text-white hover:bg-blue-600');
                        $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
                    }
                });

                // Initialize Casemark DataTable
                var casemarkTable = $('#casemarkTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    autoWidth: false,
                    pageLength: 10,
                    dom: '<"flex flex-col-reverse gap-y-2 md:flex-row md:justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    ajax: {
                        url: "{{ route('casemark.data') }}",
                        type: 'GET',
                        data: function(d) {
                            const dateRange = $('#date-range').val();
                            if (dateRange) {
                                const dates = dateRange.split(' to ');
                                d.start_date = dates[0];
                                d.end_date = dates[1] || dates[0];
                            }
                            const selectedDn = $('#statusFilter').val();
                            d.dn_no = selectedDn === '' ? null : selectedDn;
                        }
                    },
                    columns: [{
                            data: null,
                            width: '5%',
                            className: 'px-6 py-3',
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + 1 + meta.settings._iDisplayStart;
                            }
                        },
                        // {
                        //     "data": '',
                        //     "render": function(data, type, row, meta) {
                        //         return meta.row + 1; // This will number the rows starting from 1
                        //     },
                        //     width: '5%',
                        //     className: 'px-6 py-3'
                        // },

                        {
                            data: 'isMatched',
                            name: 'isMatched',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'casemark_no',
                            name: 'casemark_no',
                            width: '15%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'count_kanban',
                            name: 'count_kanban',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'qty_kanban',
                            name: 'qty_kanban',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'part_no',
                            name: 'part_no',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'part_name',
                            name: 'part_name',
                            width: '15%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'box_type',
                            name: 'box_type',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'qty_per_box',
                            name: 'qty_per_box',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'dn_no',
                            name: 'dn_no',
                            width: '10%',
                            className: 'px-6 py-3'
                        },
                    ],
                    language: {
                        emptyTable: "No data available",
                        zeroRecords: "No matching records found",
                        lengthMenu: "Show _MENU_ entries",
                        search: "",
                        searchPlaceholder: "Search Casemark..."
                    },
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hover:bg-gray-50');
                    },
                    drawCallback: function() {
                        $('.paginate_button').addClass(
                            'cursor-pointer px-3 py-1 bg-white border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200'
                        );
                        $('.paginate_button.current').addClass('bg-blue-500 text-white hover:bg-blue-600');
                        $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
                    }
                });

                // Handle table toggle
                $('#tableToggle').change(function() {
                    const isCasemark = $(this).is(':checked');
                    $('#dnTableContainer').toggle(!isCasemark);
                    $('#casemarkTableContainer').toggle(isCasemark);
                    $('#dateRangeContainer').toggle(!isCasemark);
                    if (isCasemark) {
                        casemarkTable.ajax.reload();
                    } else {
                        dnTable.ajax.reload();
                    }
                });

                // Handle filter change
                $('#statusFilter').change(function() {
                    if ($('#tableToggle').is(':checked')) {
                        casemarkTable.ajax.reload();
                    } else {
                        dnTable.ajax.reload();
                    }
                });

                // Handle print label click (DN table only)
                $(document).on('click', '.print-label-btn', function() {
                    const dnNo = $(this).data('dn');
                    const button = $(this);
                    console.log(dnNo)
                    button.prop('disabled', true).addClass('opacity-60');

                    $.ajax({
                        url: "{{ route('transaction.printDn') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            dn_no: dnNo,
                        },
                        success: function() {
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Print job sent');
                            } else {
                                alert('Print job sent');
                            }
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'Print failed';
                            if (typeof toastr !== 'undefined') {
                                toastr.error(msg);
                            } else {
                                alert(msg);
                            }
                        },
                        complete: function() {
                            button.prop('disabled', false).removeClass('opacity-60');
                        }
                    });
                });

                // Customize the length select and search inputs
                $('.dataTables_length select').addClass('px-4 py-1 border border-gray-300 rounded-md ');
                $('.dataTables_filter input').addClass(
                    'px-4 py-1 border border-gray-300 rounded-md text-md placeholder:text-sm placeholder:italic');
                // $('.dataTables_filter').addClass('w-lvw md:w-fit px-8 md:px-0 mx-auto');

                // Customize pagination buttons
                $('.dataTables_paginate').addClass('flex gap-4 items-center');
                $('.dataTables_paginate .paginate_button').addClass(
                    'px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200'
                );
                $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-500 text-white hover:bg-blue-600');
                $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');

                // Customize info text
                $('.dataTables_info').addClass('text-sm text-gray-600');
            });

            function getExportUrl() {
                const dateRange = $('#date-range').val();
                let dn_no = $('#statusFilter').val();
                let url = "{{ route('export.transactions') }}?";
                if (dateRange) {
                    const dates = dateRange.split(' to ');
                    url += `start_date=${dates[0]}&end_date=${dates[1] || dates[0]}&`;
                }
                if (dn_no) {
                    url += `dn_no=${dn_no}`;
                }
                return url;
            }

            $('#exportBtn').on('click', function(e) {
                e.preventDefault();
                window.location.href = getExportUrl();
            });
        </script>
    </div>
</x-layout>
