<h2>New Product Subscription Alert!</h2>
<ul>
    <li><b>Product:</b> {{ $subscription->product->name ?? '-' }}</li>
    <li><b>Grind Size:</b> {{ $subscription->grind_size }}</li>
    <li><b>Weight/Size:</b> {{ $subscription->weight }}</li>
    <li><b>Week:</b> {{ $subscription->week }}</li>
    <li><b>Month:</b> {{ $subscription->month }}</li>
    <li><b>User:</b> {{ $subscription->user->name ?? '-' }}</li>
</ul>
