<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Préconisation</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            text-align: left;
        }

        .header {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .customer-details {
            margin-bottom: 20px;
        }

        .customer-details th,
        .customer-details td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .items-table {
            border: 1px solid #ddd;
        }

        .items-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Préconisation #{{ $receipt->id }}</h1>
        <p>{{ $receipt->preconisation_date}}</p>
    </div>

    <div class="customer-details">
        <table>
            <tr>
                <th>Agriculteur</th>
                <td>{{ $receipt->farmer->fullnmae }}</td>
            </tr>
        </table>
    </div>

    <div class="items-table">
        <table style="overflow: hidden;">

            <tbody>
                <tr class="header">
                    <td>Intrant</td>
                    <td>Prix</td>
                    <td>Qty</td>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->intrant->name_fr }}</td>
                        <td>{{ number_format($item->price, 2, '.', ' ') }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        <p style="  font-size: 1.2em; ">Total : {{    number_format($receipt->total_amount, 2, '.', ' ')}} DA</p>
        <p style="  font-size: 1.2em; ">Ingénieur: {{ $receipt?->createdBy?->name }}</p>
    </div>

    <div class="footer">
        <p>Note</p>
        <p>{{ $receipt->note }}</p>
    </div>

</body>

</html>