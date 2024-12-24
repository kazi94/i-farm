
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Préconisation N°{{ $receipt['id']}}</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 0;
            margin: 0;
            
            width: 288px;
            border: 1px solid black;
        }


        th {
            text-align: left;
        }

                .preconistation {
            padding: 0;
            margin: 0;
            width: 288px;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="preconistation">
        <span>**********************************</span>

        <p style="margin-bottom: 0; margin-top:0">
            <b>Préconisation #{{ $receipt['id'] }}</b> <br>
            <br>
            <b>
                {{ $receipt['farm']->name }} - {{ $receipt['farm']->culture->name }}
            </b>
            <br>
            <b>Superficie:</b>{{ $receipt['farm']->area }} {{ $receipt['farm']->unit->name }}
        </p>
        <span>**********************************</span>
        <br>
        <b style="    display: block; font-size: small; text-align: left; margin-left: 43px; margin-bottom: 10px;">
            Agriculteur: {{ $receipt['farmer']->fullname }} {{ $receipt['farmer']->wilaya->name }}
        </b>
        <div>
            <table style="width: 100%; text-align: left; table-layout: fixed; margin-bottom: 2px; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr>
                        <td style="word-wrap: break-word; border: 1px solid black;">Depredateur</td>
                        <td style="word-wrap: break-word; border: 1px solid black;">Intrant</td>
                        <td style="word-wrap: break-word; border: 1px solid black;">Qty</td>
                        <td style="word-wrap: break-word; border: 1px solid black;">Dose</td>
                        <td style="word-wrap: break-word; border: 1px solid black;">Application</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receipt['preconisationItems'] as $item)
                        @foreach ($item['traitments'] as $intrantCulture)
                            <tr>
                                @if ($loop->index == 0)
                                    <td @if ($loop->index == 0) rowspan="{{ count($item['traitments']) }}" @endif style="word-wrap: break-word; border: 1px solid black;">
                                        <b>{{ $item['depredateur']['name'] }}</b>
                                    </td>
                                @endif
                                <td style="word-wrap: break-word; border: 1px solid black;">{{ $intrantCulture['intrant'] ?? '/' }}</td>
                                <td style="word-wrap: break-word; border: 1px solid black;">{{ $intrantCulture['quantity'] ?? '/' }} {{ $intrantCulture['unit']['name'] ?? '/' }}</td>
                                <td style="word-wrap: break-word; border: 1px solid black;">{{ $intrantCulture['dose'] ?? '/' }}</td>
                                <td style="word-wrap: break-word; border: 1px solid black;">{{ $intrantCulture['usage_mode'] ?? '/' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    <div>
        <h3 style="margin-bottom: 0px">Note</h3>
        <div class="fotter">

            <p>{!! html_entity_decode($receipt['note'])!!}</p>
        </div>
    </div>
        <p>**********************************</p>
        <b>{{ $receipt['date_preconisation']}}</b>
        <br>
        <span><b>Merci pour votre confiance !</b></span>
        <br>
    </div>

</body>

</html>
