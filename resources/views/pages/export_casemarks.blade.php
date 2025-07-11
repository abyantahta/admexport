<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Status</th>
            <th>DN No</th>
            <th>Casemark No</th>
            <th>Count Kanban</th>
            <th>Qty Kanban</th>
            <th>Part No</th>
            <th>Part Name</th>
            <th>Box Type</th>
            <th>Qty Per Box</th>
        </tr>
    </thead>
    <tbody>
        @foreach($casemarks as $casemark)
            <tr>
                <td>{{ $loop->index +1}}</td>
                <td>{{ $casemark->isMatched ? 'Matched' : 'Unmatched' }}</td>
                <td>{{ $casemark->dn_no }}</td>
                <td>{{ $casemark->casemark_no }}</td>
                <td>{{ $casemark->count_kanban }}</td>
                <td>{{ $casemark->qty_kanban }}</td>
                <td>{{ $casemark->part_no }}</td>
                <td>{{ $casemark->part_name }}</td>
                <td>{{ $casemark->box_type }}</td>
                <td>{{ $casemark->qty_per_box }}</td>
            </tr>
        @endforeach
    </tbody>
</table> 