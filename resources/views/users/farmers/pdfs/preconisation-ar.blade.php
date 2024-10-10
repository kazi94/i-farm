<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″>
    <title>التوصية رقم {{ $receipt->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0 auto;
            padding: 0;
            direction: rtl;
            text-align: right;
            border-radius: 5px;
            border: 1px solid black;
        }

        .main {
            margin: 0 auto;
            padding: 10px;

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
            text-align: right;
        }

        .header {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 20px;
            font-weight: bold;
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
    <script>
        window.print();
    </script>
</head>

<body lang="ar">

    <div class="main">
        <div class="header">
            <h1>التوصية رقم {{ $receipt->id }}</h1>
            <p>{{ $receipt->preconisation_date}}</p>
        </div>

        <div class="customer-details">
            <table>
                <thead>
                    <th>
                        <h3>المنتج</h3>
                    </th>
                    <th>
                        <h3>الحقل</h3>
                    </th>
                </thead>
                <tr>
                    <td>{{ $receipt->farmer->fullname }}</td>
                    <td><b>المساحة: </b>{{ $receipt->farm->area }} {{ $receipt->farm->unit->name_ar }}</td>
                </tr>
                <tr>
                    <td> {{ $receipt->farmer->wilaya->name }}</td>
                    <td><b>الحقل: </b>{{ $receipt->farm->culture->name }}</td>
                </tr>
            </table>
        </div>

        <div class="items-table">
            <table style="overflow: hidden;">

                <tbody>
                    <tr class="header">
                        <td>المرض</td>
                        <td>الدواء</td>
                        <td>الكمية</td>
                        <td>الجرعة</td>
                        <td>طريقة الاستخدام</td>
                        <td>السعر</td>
                    </tr>

            @foreach ($receipt['preconisationItems'] as $item)
               @foreach ($item['traitments'] as $intrantCulture )
                    <tr>
                        @if ($loop->index == 0)
                            <td @if ($loop->index == 0) rowspan="{{ count($item['traitments']) }}" @endif>
                                <b>{{ $item['depredateur']['name'] }}</b>
                            </td>
                        @endif
                        <td>{{ $intrantCulture['intrant']  ?? '/'}}</td>
                        <td>{{ $intrantCulture['quantity']  ?? '/'}} {{ $intrantCulture['unit']['name']  ?? '/'}}</td>
                        <td>{{ $intrantCulture['dose_ar']  ?? '/'}}</td>
                        <td>{{ $intrantCulture['usage_mode']  ?? '/'}}</td>
                        <td>{{ number_format($intrantCulture['quantity'] * $intrantCulture['price'], 2, '.', ' ')  }} دج</td>
                    </tr>
                @endforeach
            @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <p style="  font-size: 1.2em; "><b>المجموع:</b> {{number_format($receipt['total_amount'], 2, '.', ' ')}} دج
            </p>
            <p style="  font-size: 1.2em; "><b>المهندس:</b> {{ ucfirst($receipt['createdBy']->name) }}</p>
        </div>

        <div>
            <h3 style="margin-bottom: 0px">ملاحظات</h3>
            <div class="fotter">

                <p>{!! html_entity_decode($receipt->note)!!}</p>
            </div>
        </div>
    </div>

</body>

</html>
