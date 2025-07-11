{{--  --}}
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Status</th>
            <th>DN No</th>
            <th>Casemark No</th>
            <th>Sequence</th>
            <th>Part Label QC</th>
            <th>Sequence Label QC</th>
            <th>Lot No</th>
            <th>Created At</th>
            {{-- <th>Box Type</th>
            <th>Qty Per Box</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $loop->index +1}}</td>
                <td>{{ $transaction->status}}</td>
                <td>{{ $transaction->dn_no }}</td>
                <td>{{ $transaction->casemark_no }}</td>
                <td>{{ $transaction->seq_no_kanban }}</td>
                <td>{{ $transaction->part_no_label }}</td>
                <td>{{ $transaction->seq_no_label }}</td>
                <td>{{ $transaction->lot_no }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at) }}</td>
                {{-- <td>{{ $transactions->box_type }}</td>
                <td>{{ $transactions->qty_per_box }}</td> --}}
            </tr>
        @endforeach
    </tbody>
</table> 