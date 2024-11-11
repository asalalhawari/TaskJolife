<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h1 class="display-5">Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="lead">Date: {{ $invoice->invoice_date }}</p>
            <p>Client: <strong>{{ $invoice->client->name }}</strong></p>
        </div>

        <table class="table table-striped table-hover table-bordered">
            <thead class="table-primary">
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $item)
               
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total Amount</td>
                    <td class="fw-bold">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>


    </div>
</body>
<script>
    window.print(); 
    window.onafterprint = () => window.close(); // Close the tab after printing
</script>
</html>
