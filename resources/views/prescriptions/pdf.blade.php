<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width : 100%; border-collapse: collapse; margin-top : 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align : left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom : 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Prescription</h2>
        <p>Patient: <strong>{{ $prescription->patient->name }}</strong></p>
        <p>Date: <strong>{{ date('d M Y', strtotime($prescription->created_at)) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr no.</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prescription->prescriptionDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->medicine->medicine_name }}</td>
                    <td>{{ $detail->dosage->dosage }}</td>
                    <td>{{ $detail->comments }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
