<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 18px;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header" style="margin-top: 20px">
        <h1>PT.SANKEI DHARMA INDONESIA</h1>
        <h2 style="margin-top:-10px">Confirmation Packing D38H(DCWA/D258)</h2>
    </div>
    
    <table style="margin-top: -10px">
        <thead>
            <tr>
                <th>Casemark No</th>
                <th>DN No</th>
                <th>Part No</th>
                <th>Part Name</th>
                <th>Qty Per Box</th>
                <th>Qty Kanban</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($casemarks as $casemark)
                <tr>
                    <td>{{ $casemark->casemark_no }}</td>
                    <td>{{ $casemark->dn_no }}</td>
                    <td>{{ $casemark->part_no }}</td>
                    <td>{{ $casemark->part_name }}</td>
                    <td>{{ $casemark->qty_per_box }}</td>
                    <td>{{ $casemark->qty_kanban }}</td>
                </tr>
                @endforeach
        </tbody>
    </table>

    <table style="width:50%; margin-left:auto;margin-top:30px">

        <tbody>
            {{-- @foreach ($casemarks as $casemark) --}}
            <tr >
                <td style="text-align: center" colspan="2">PT.SANKEI DHARMA INDONESIA</td>
                <td style="text-align: center" colspan="2">VENDOR</td>
                {{-- <td></td> --}}
            </tr>
            <tr style="height: 70px">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
                {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> --}}
                <tr>
                    <td style="text-align:center;">Admin Delivery</td>
                    <td style="text-align:center">Warehouse</td>
                    <td style="text-align:center">Vendor Packing</td>
                    <td style="text-align:center">Vendor Packing</td>
                </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>

</body>
</html>

