<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Updated</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <p>Dear Customer,</p>

    <p>Your order <strong>{{ $order->code }}</strong> has been updated by admin. Please review the changes below:</p>

    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; max-width: 900px;">
        <thead>
            <tr style="background: #f5f5f5;">
                <th align="left">Product</th>
                <th align="left">SKU (Old -> New)</th>
                <th align="left">Quantity (Old -> New)</th>
                <th align="left">Price (Old -> New)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($changes as $change)
                <tr>
                    <td>{{ $change['product_name'] }}</td>
                    <td>{{ $change['old_sku'] !== '' ? $change['old_sku'] : '-' }} -> {{ $change['new_sku'] !== '' ? $change['new_sku'] : '-' }}</td>
                    <td>{{ $change['old_qty'] }} -> {{ $change['new_qty'] }}</td>
                    <td>{{ number_format((float) $change['old_price'], 2) }} -> {{ number_format((float) $change['new_price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 16px;">Updated total amount: <strong>{{ single_price($order->grand_total) }}</strong></p>
    <p>Thank you.</p>
</body>
</html>

