<x-layout>
    @php
        // dd($$activeDn)
    @endphp
    <div class="w-full h-lvh">
        <div class=" mt-6 md:pt-4">
            @if ($interlock && $interlock->isLocked)
            <div class="bg-[rgba(0,0,0,0.8)] absolute top-0 left-0 w-full h-lvh flex items-center justify-center z-[9999]">
                <div class="w-full mx-0 scale-75 sm:scale-100 sm:w-96 rounded-lg overflow-hidden sm:mx-4 flex flex-col items-center justify-center bg-white pb-4">  
                    <h1 class="text-xl text-center font-bold bg-red-400 w-full h-12 flex items-center justify-center text-white mb-2">This page is Locked</h1>
                    <h2 class="text-sm">Mismatch at <b>{{ $interlock->created_at }}</b></h2>
                    <h2 class="text-sm">Part Kbn : <b>{{ $interlock->part_no_kanban }}</b></h2>
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
                                class="pl-4 w-full border-1 shadow-md border-gray-700 form-control h-7 md:h-10 " id="passkey"
                                name="passkey" required autofocus>
                                <button class="bg-green-700 mx-auto px-4 rounded-sm text-white" type="submit">Submit</button>
                        </div>
                    </form>
                
                </div>
            </div>
            @endif
            <h5 class="text-center  sm:text-3xl text-md font-bold mb-2 text-green-600 italic">KANBAN MATCHING</h5>
            <!-- Success message -->
            @if (session('success'))
                <div
                    class="bg-yellow-500 text md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                    {!! session('success') !!}
                </div>
            @endif
            <!-- Error message -->
            {{-- <div class="bg-red-400 inline-block mx-auto px-6 py-1">{!! $errors->first() !!}DBSIDSOAUHDSA</div> --}}

            @if ($errors->any())
                <div class="bg-red-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm"
                    role="alert">
                    {{-- <x-radix-cross-circled class="w-6" /> --}}
                    {{-- <x-zondicon-close-outline /> --}}
                    <x-codicon-error class="w-8" />
                    {{-- {{ $value }} --}}
                    {!! $errors->first() !!}
                    {{-- Whoops! There are some problems --}}
                </div>
            @endif

            @if (session('message'))
                <div
                    class="bg-yellow-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                    {!! session('message') !!}
                </div>
            @endif
            @if (session('reset_error'))
                <div
                    class="bg-red-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                    {!! session('reset_error') !!}
                </div>
            @endif

            @if (session('message-match'))
                <div
                    class="bg-green-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                    {!! session('message-match') !!}
                </div>
            @endif

            @if (session('message-reset'))
                <div class="bg-yellow-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                    {!! session('message-reset') !!} 
                </div>
            @endif
            <!-- Barcode Matching Form -->
            {{-- <form class="" action="" method="POST"> --}}
            <form class="" action="{{ route('matching.store') }}" method="POST">
                @csrf
                <div class="md:mb-4 md:mt-3 flex justify-center">
                    {{-- <label for="barcode" class="form-label">SCAN BARCODE</label> --}}
                    {{-- <input type="text" class="form-control" id="barcode" name="barcode" readonly  required autofocus> --}}
                    <input type="text" placeholder="Scan Barcode..."
                        class="pl-6 md:pl-12 w-full md:w-1/2 border-[2px] md:border-[3px] rounded-full flex items-center justify-center placeholder:text-xs text-xs md:text-lg md:placeholder:text-lg placeholder:italic border-solid border-blue-400 form-control h-7 md:h-10 "
                        id="barcode" name="barcode" readonly onfocus="this.removeAttribute('readonly');" required
                        autofocus>
                </div>
                <div class="container mx-auto mt-3">
                    @if (session('message-no-match'))
                        <div
                            class="bg-red-500  md:px-16 flex items-center justify-center gap-1 text-center inline-block py-2 px-8 rounded-md font-bold text-white text-sm">
                            {{-- <i class="fas fa-circle-xmark text-danger"></i> --}}
                            {!! session('message-no-match') !!}
                        </div>
                    @endif
                    <div class="flex w-full gap-2 xl:gap-10 mb-3 md:mb-4 ">
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="dn_no" class="md:text-lg text-xs font-semibold mb-2">DN</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="dn_no" name="dn_no" value="{{ $activeDn['dn_no'] }}" disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                        <div class="w-1/2 flex gap-2 xl:gap-10">
                            <div class="w-1/2">
                                <div class="flex flex-col">
                                    <label for="qty_casemark"
                                        class="md:text-lg text-xs font-semibold mb-2">Casemark</label>
                                    <input type="text"
                                        class="border-2 border-black rounded-md h-6 text-center text-sm md:h-8 text-xs md:text-base w-full"
                                        id="qty_casemark" name="qty_casemark" value="{{ $activeDn['qty_casemark'] }}"
                                        disabled>
                                    {{-- <h2 class="">halo semuanya</h2> --}}
                                </div>
                            </div>
                            <div class="w-1/2">
                                <div class="flex flex-col">
                                    <label for="count_casemark"
                                        class="md:text-lg text-xs font-semibold mb-2">Count</label>
                                    <input type="text"
                                        class="border-2 border-black rounded-md h-6 text-center text-sm md:h-8 text-center text-xs md:text-base w-full"
                                        id="count_casemark" name="count_casemark"
                                        value="{{ $activeDn['count_casemark'] }}" disabled>
                                    {{-- <h2 class="">halo semuanya</h2> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full gap-2 xl:gap-10 mb-3 md:mb-4 ">
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="active_casemark" class="md:text-lg text-xs font-semibold mb-2">Casemark
                                    No</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="active_casemark" name="active_casemark"
                                    value="{{ $activeCasemark['casemark_no'] }}" disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                        <div class="w-1/2 flex gap-2 xl:gap-10">
                            <div class="w-1/2">
                                <div class="flex flex-col">
                                    <label for="qty_kanban" class="md:text-lg text-xs font-semibold mb-2">Kanban</label>
                                    <input type="text"
                                        class="border-2 border-black rounded-md h-6 text-sm md:h-8 text-center text-xs md:text-base w-full"
                                        id="qty_kanban" name="qty_kanban" value="{{ $activeCasemark['qty_kanban'] }}"
                                        disabled>
                                    {{-- <h2 class="">halo semuanya</h2> --}}
                                </div>
                            </div>
                            <div class="w-1/2">
                                <div class="flex flex-col">
                                    <label for="count_kanban"
                                        class="md:text-lg text-xs font-semibold mb-2">Count</label>
                                    <input type="text"
                                        class="border-2 border-black rounded-md h-6 text-sm md:h-8 text-center text-xs md:text-base w-full"
                                        id="count_kanban" name="count_kanban"
                                        value="{{ $activeCasemark['count_kanban'] }}" disabled>
                                    {{-- <h2 class="">halo semuanya</h2> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full gap-2 xl:gap-10 mb-3 md:mb-4 ">
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="part_no_kanban" class="md:text-lg text-xs font-semibold mb-2">Part No
                                    Kanban</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="part_no_kanban" name="part_no_kanban" value="{{ session('part_no_kanban') }}"
                                    disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="part_no_label" class="md:text-lg text-xs font-semibold mb-2">Part No
                                    QC</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="part_no_label" name="part_no_label" value="{{ session('part_no_label') }}"
                                    disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full gap-2 xl:gap-10 mb-3 md:mb-4 ">
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="seq_kanban" class="md:text-lg text-xs font-semibold mb-2">Seq No
                                    Kanban</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="seq_kanban" name="seq_kanban" value="{{ session('seq_kanban') }}" disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="flex flex-col">
                                <label for="seq_label" class="md:text-lg text-xs font-semibold mb-2">Seq No
                                    QC</label>
                                <input type="text"
                                    class="border-2 border-black rounded-md h-6 pl-1 md:pl-4 text-sm md:h-8 text-xs md:text-base w-full"
                                    id="seq_label" name="seq_label" value="{{ session('seq_label') }}" disabled>
                                {{-- <h2 class="">halo semuanya</h2> --}}
                            </div>
                        </div>
                    </div>





                    {{-- </div> --}}

                    <div class="mt-6 flex-col sm:flex-row flex gap-x-4  gap-y-2 mx-auto w-full sm:w-fit">
                        {{-- <button class="w-full h-3 bg-green-200">sdas</button> --}}
                        <button type="submit"
                            class="block mx-auto bg-green-700 text-sm sm:text-lg py-1 w-full sm:w-44 font-bold italic  rounded-lg text-white btn-sm :btn-md">Submit
                        </button>
                        <button type="button"
                            onclick="openResetModal()"
                            class="bg-yellow-500 font-bold text-sm sm:text-lg italic py-1 w-full sm:w-44 rounded-lg block mx-auto btn-sm :btn-md">Reset Session
                        </button>
                    </div>
            </form>

            <!-- Reset Session Modal -->
            <div id="resetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 w-80 flex flex-col items-center">
                    <h2 class="text-lg font-bold mb-2 text-red-600">Reset Session</h2>
                    <form id="resetForm" method="POST" action="{{ route('matching.resetWithPassword') }}">
                        @csrf
                        <input type="password" name="reset_password" id="reset_password" class="border border-gray-400 rounded px-3 py-2 w-full mb-3" placeholder="Enter password" required autofocus>
                        <div class="flex gap-2 justify-center">
                            <button type="submit" class="bg-yellow-500 text-white w-1/2 py-1 rounded font-bold">Confirm</button>
                            <button type="button" onclick="closeResetModal()" class="bg-gray-300 w-1/2 py-1 rounded">Cancel</button>
                        </div>
                    </form>
                    {{-- @if(session('reset_error'))
                        <div class="text-red-500 mt-2 text-sm">{{ session('reset_error') }}</div>
                    @endif --}}
                </div>
            </div>

            <!-- Reset Button triggers modal -->

            <script>
                function openResetModal() {
                    document.getElementById('resetModal').classList.remove('hidden');
                    setTimeout(() => document.getElementById('reset_password').focus(), 100);
                }
                function closeResetModal() {
                    document.getElementById('resetModal').classList.add('hidden');
                    document.getElementById('reset_password').value = '';
                }
                // Optional: Close modal on ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeResetModal();
                });
            </script>

        </div></br>
        <div class="container overflow-x-scroll">
            <!-- Transaction Summary Table -->
            {{-- <h5>Transaction Summary</h5> --}}
            <table id="transactionsTable"
                class="mt-3 h-2 w-full text-center text-xs text-left rtl:text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">
                <thead class=" text-xs text-black uppercase bg-green-700 dark:text-gray-400">
                    <th scope="col" class="px-6 py-4 text-white text-xs">No</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Status</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Casemark</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Sequence Kanban</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Label QC</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Seq No QC</th>
                    <th scope="col" class="px-6 py-4 text-white text-xs">Created At</th>
                    </tr>

                </thead>
                <tbody class="text-gray-800 font-semibold -pt-3 text-xs md:text-base">
                </tbody>
            </table>
        </div>

        <!-- Add Bootstrap JS (optional, for Bootstrap components like modals or tooltips) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        {{-- @push('scripts') --}}
        <!-- Add jQuery and DataTables JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <!-- Add Flatpickr CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTable
                $('#transactionsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    autoWidth: false,
                    pageLength: 10,
                    dom: '<"flex text-xs md:text-base flex-col-reverse gap-y-2 md:flex-row md:justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    ajax: {
                        url: "{{ route('transactions.data') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '5%',
                            className: 'px-6 py-3'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            width: '100px'
                        },
                        {
                            data: 'casemark_no',
                            name: 'casemark_no',
                            // width: '100px'
                        },
                        {
                            data: 'seq_no_kanban',
                            name: 'seq_no_kanban',
                            className: 'nowrap',
                            width: '100px'
                        },
                        {
                            data: 'part_no_label',
                            name: 'part_no_label'
                        },
                        {
                            data: 'seq_no_label',
                            name: 'seq_no_label',
                            orderable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: false
                        },
                        // {
                        //     data: 'status',
                        //     name: 'status',
                        //     orderable: false,
                        //     searchable: false
                        // }
                    ],
                    language: {
                        emptyTable: "No data available",
                        zeroRecords: "No matching records found",
                        lengthMenu: "Show _MENU_ entries",
                        search: "",
                        searchPlaceholder: "Search..."
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

                // Barcode input handling
                const barcodeInput = document.getElementById('barcode');

                barcodeInput.addEventListener('focus', function() {
                    barcodeInput.removeAttribute('readonly');
                });

                barcodeInput.addEventListener('blur', function() {
                    barcodeInput.setAttribute('readonly', true);
                });

                barcodeInput.addEventListener('input', function() {
                    setTimeout(() => {
                        barcodeInput.setAttribute('readonly', true);
                    }, 1000);
                });

                // Customize the length select and search inputs
                $('.dataTables_length select').addClass('px-4 py-1 border border-gray-300 rounded-md ');
                $('.dataTables_filter input').addClass(
                    'px-4 py-1 border border-gray-300 rounded-md text-md placeholder:text-sm placeholder:italic');
                // $('.dataTables_filter').addClass('w-lvw md:w-fit px-8 md:px-0 mx-auto');

                // Customize pagination buttons
                $('.dataTables_paginate').addClass('flex text-xs sm:text-base gap-4 items-center');
                $('.dataTables_paginate .paginate_button').addClass(
                    'px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200'
                );
                $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-500 text-white hover:bg-blue-600');
                $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');

                // Customize info text
                $('.dataTables_info').addClass('hidden sm:block text-gray-600');
            });
        </script>
        {{-- @endpush --}}
    </div>
</x-layout>
