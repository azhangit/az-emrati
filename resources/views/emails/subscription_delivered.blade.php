<h2>Subscription Delivery Update</h2>
<p>Your subscription delivery has been marked as delivered.</p>
<ul>
    <li><b>Product:</b> {{ $subscription->product->name ?? '-' }}</li>
    <li><b>Grind Size:</b> {{ $subscription->grind_size ?? '-' }}</li>
    <li><b>Weight/Size:</b> {{ $subscription->weight ?? '-' }}</li>
    <li><b>Status:</b> {{ ucfirst($subscription->status ?? 'active') }}</li>
</ul>
<p>Thank you for choosing us.</p>

