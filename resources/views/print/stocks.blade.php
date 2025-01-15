<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>طباعة الحركات</title>
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 1.6cm;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .totals {
            font-weight: bold;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body">
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>المادة</th>
                <th>تفاصيل المادة</th>
                <th>الجهة</th>
                <th>إدخال</th>
                <th>إخراج</th>
                <th>التفاصيل</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->created_at }}</td>
                    <td>{{ $stock->item->name }}</td>
                    <td>{{ $stock->item->description }}</td>
                    <td>{{ $stock->user->role }}</td>
                    <td>{{ $stock->in_quantity }}</td>
                    <td>{{ $stock->out_quantity }}</td>
                    <td>{{ $stock->details }}</td>
                    <td>{{ $stock->approved ? 'مدقق' : 'غير مدقق' }}</td>
                </tr>
            @endforeach
            <tr class="totals">
                <td colspan="4">المجموع</td>
                <td>{{ $totals['in_quantity'] }}</td>
                <td>{{ $totals['out_quantity'] }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
