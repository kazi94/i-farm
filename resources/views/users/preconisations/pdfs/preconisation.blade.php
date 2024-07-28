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

        .row {
            display: flex;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            width: 100%;
            height: 100px;
            border-radius: 5px;
            border: 1px solid black;
            padding: 10px;
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
            <thead>
                <th>
                    <h3>Agriculteur</h3>
                </th>
                <th>
                    <h3>Culture</h3>
                </th>
            </thead>

            <tr>
                <td>{{ $receipt->farmer->fullname }}</td>
                <td>{{ $receipt->farm->culture->name }}</td>
            </tr>

            <tr>
                <td>{{ $receipt->farmer->wilaya->name }}</td>
                <td><b>Superficie:</b>{{ $receipt->farm->area }} {{ $receipt->farm->unit->name }}</td>
            </tr>
        </table>
    </div>

    <div class="items-table">
        <table style="overflow: hidden;">

            <tbody>
                <tr class="header">
                    <td>Intrant</td>
                    <td>Qty</td>
                    <td>Dose</td>
                    <td>Mode d'application</td>
                    <td>Prix</td>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td><b>{{ $item->intrant->name_fr }}</b></td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->dose }}</td>
                        <td>{{ $item->fr_usage_mode }}</td>
                        <td>{{ number_format($item->price, 2, '.', ' ') }} DA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        <p style="  font-size: 1.2em; ">Total : {{    number_format($receipt->total_amount, 2, '.', ' ')}} DA</p>
        <p style="  font-size: 1.2em; ">Ingénieur: {{ $receipt?->createdBy?->name }}</p>
    </div>

    <div>
        <h3 style="margin-bottom: 0px">Note</h3>
        <div class="fotter">

            <p>{!! html_entity_decode($receipt->note)!!}</p>
        </div>
    </div>

</body>

</html>
