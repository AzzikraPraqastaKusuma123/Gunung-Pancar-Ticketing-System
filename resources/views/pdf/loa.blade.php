<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $loa->document_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .signatures { margin-top: 50px; width: 100%; }
        .signatures td { width: 50%; text-align: center; }
        .signature-box { height: 100px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LETTER OF AGREEMENT (LOA)</h2>
        <p>No: {{ $loa->document_number }}</p>
    </div>

    <div class="content">
        <p>This Letter of Agreement is made between:</p>
        <p>
            <strong>Gunung Pancar Camping Ground</strong><br>
            And<br>
            <strong>{{ $loa->lead->name }}</strong>
        </p>

        <h3>Activity Details</h3>
        <table class="table">
            <tr>
                <th>Customer Name</th>
                <td>{{ $loa->lead->name }}</td>
            </tr>
            <tr>
                <th>Customer Phone</th>
                <td>{{ $loa->lead->phone }}</td>
            </tr>
            <tr>
                <th>Activity Type</th>
                <td>{{ ucfirst($loa->lead->activity_type) }}</td>
            </tr>
            <tr>
                <th>Event Date</th>
                <td>{{ $loa->lead->event_date ? \Carbon\Carbon::parse($loa->lead->event_date)->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Pax</th>
                <td>{{ $loa->lead->pax }} Persons</td>
            </tr>
        </table>

        <h3>Financial Details</h3>
        <table class="table">
            <tr>
                <th>Total Amount</th>
                <td>Rp {{ number_format($loa->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Down Payment</th>
                <td>Rp {{ number_format($loa->down_payment, 0, ',', '.') }}</td>
            </tr>
        </table>

        <h3>Terms and Conditions</h3>
        <div>
            {!! nl2br(e($loa->terms_and_conditions)) !!}
        </div>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <p>Agreed by Customer</p>
                <div class="signature-box"></div>
                <p><strong>{{ $loa->signed_by_customer ?? $loa->lead->name }}</strong></p>
            </td>
            <td>
                <p>Authorized by Management</p>
                <div class="signature-box"></div>
                <p><strong>{{ $loa->signed_by_company ?? 'Gunung Pancar' }}</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>
