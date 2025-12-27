<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\SmsTemplate;
use Auth;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Utility\NotificationUtility;
use CoreComponentRepository;

use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OrderController extends Controller
{

    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }
 



public function exportCsv(Request $request): StreamedResponse
{
    \Log::info('Export CSV requested');
    if (function_exists('set_time_limit')) @set_time_limit(0);

    $selectedIds = collect((array)$request->input('ids'))->filter()->values()->all();

    $q = Order::query()
        ->select([
            'id','code','user_id','guest_id',
            'grand_total','delivery_status','payment_type','payment_status','created_at',
            'shipping_address','additional_info',
        ])
        ->with([
            'user:id,name,email',
            'shop:id,name,user_id',
            'orderDetails:id,order_id,product_id,quantity,price,tax,shipping_cost,variation,payment_status,delivery_status',
            'orderDetails.product:id,name,auction_product,category_id',
            'orderDetails.product.stocks:id,product_id,sku,variant',
            'orderDetails.product.main_category:id,name',
            'orderDetails.product.categories:id,name',
        ])
        ->withCount('orderDetails')
        ->when(!empty($selectedIds), fn($qq) => $qq->whereIn('id', $selectedIds))
        ->orderBy('id');

    // (Optional) Agar aap new_orders se billing map use kar rahe hain to yahan rakhein,
    // warna billing ko shipping JSON se hi nikaalna hai to un helpers ka use karein.

    $filename = 'orders_selected_' . now()->format('Y-m-d_H-i') . '.csv';

    return response()->streamDownload(function () use ($q) {
        if (ob_get_level() > 0) { @ob_end_clean(); }

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // Excel-safe UTF-8 BOM

        // ----- Header: Export All ke headers + Product Qty + Additional Info + Billing + NEW Shipping full -----
        fputcsv($out, [
            'ID','Order Code',
            'User Name','User Email',
            'Num. of Products','Product Names','Product SKUs','Product Categories',
            'Product Quantity',
            'Customer Label',
            'Amount','Delivery Status','Payment Method','Payment Status',

            // Shipping (pehle jaisa)
            'Ship Country','Ship State','Ship City',
            // NEW: full shipping fields from shipping_address JSON
            'Ship Name','Ship Email','Ship Phone','Ship Address','Ship Postal Code',

            'Created At',
            'Additional Info',

            // Billing full (agar chahiye to rakhein)
            'Billing Name','Billing Email','Billing Phone',
            'Billing Address1','Billing Address2',
            'Billing City','Billing State','Billing Country','Billing Postal Code',

            // Optional: Item-level extras
            'Item Delivery Status','Item Payment Status','Item Tax','Item Shipping Cost',
        ]);

        $rows = 0;

        foreach ($q->lazyById(800) as $order) {
            // Customer label
            $customerLabel = $order->user
                ? $order->user->name
                : 'Guest (' . ($order->guest_id ?? '-') . ')';

            // Prefer user table values; fallback to shipping JSON
            [$userName, $userEmail] = $this->resolveUserNameEmail($order->user, $order->shipping_address);

            // Shipping parts (country/state/city)
            [$shipCountry, $shipState, $shipCity] = $this->extractAddressParts($order->shipping_address);

            // NEW: Shipping full (name/email/phone/address/postal_code)
            [$sName,$sEmail,$sPhone,$sAddr,$sZip] = $this->extractShippingFull($order->shipping_address);

            // Additional info single-line
            $addInfo = $this->csvSafe($this->oneLine((string)($order->additional_info ?? '')));

            // Billing – agar aap new_orders map nahi use kar rahe,
            // to shipping JSON se hi billing nikaal lo (ya aap ka purana helper).
            [$bName,$bEmail,$bPhone,$bAddr1,$bAddr2,$bCity,$bState,$bCountry,$bZip] =
                $this->billingFromShippingJSON($order->shipping_address);

            // No items case → ek blank product row
            if ($order->orderDetails->isEmpty()) {
                fputcsv($out, [
                    $order->id,
                    $this->csvSafe($order->code),
                    $this->csvSafe($userName),
                    $this->csvSafe($userEmail),
                    (int) $order->order_details_count,

                    '', // Product Names
                    '', // Product SKUs
                    '', // Product Categories
                    0,  // Product Quantity

                    $this->csvSafe($customerLabel),

                    $this->moneyFmt($order->grand_total),
                    $this->labelize($order->delivery_status),
                    $this->labelize($order->payment_type),
                    $this->labelize($order->payment_status),

                    $this->csvSafe($shipCountry),
                    $this->csvSafe($shipState),
                    $this->csvSafe($shipCity),

                    $this->csvSafe($sName),
                    $this->csvSafe($sEmail),
                    $this->csvSafe($sPhone),
                    $this->csvSafe($this->oneLine($sAddr)),
                    $this->csvSafe($sZip),

                    optional($order->created_at)->format('Y-m-d H:i:s'),

                    $addInfo,

                    // Billing (from shipping JSON fallback)
                    $this->csvSafe($bName), $this->csvSafe($bEmail), $this->csvSafe($bPhone),
                    $this->csvSafe($bAddr1), $this->csvSafe($bAddr2),
                    $this->csvSafe($bCity), $this->csvSafe($bState), $this->csvSafe($bCountry), $this->csvSafe($bZip),

                    // Item-level extras
                    '', '', '0.00', '0.00',
                ]);
                if ((++$rows % 500) === 0) { fflush($out); }
                continue;
            }

            // One row per product line
            foreach ($order->orderDetails as $od) {
                $p = $od->product;

                // Product fields
                $pName = $p?->name ?? '';
                $sku   = $this->skuFromOrderDetail($od);

                // Categories (primary + many-to-many)
                $cats = [];
                if ($p?->main_category?->name) $cats[] = (string)$p->main_category->name;
                if ($p && $p->relationLoaded('categories')) {
                    foreach ($p->categories as $c) {
                        $n = trim((string)($c->name ?? '')); if ($n !== '') $cats[] = $n;
                    }
                }
                $catsStr = $cats ? implode(', ', array_unique($cats)) : '';

                $qty = (int)($od->quantity ?? 0);

                fputcsv($out, [
                    $order->id,
                    $this->csvSafe($order->code),
                    $this->csvSafe($userName),
                    $this->csvSafe($userEmail),
                    (int) $order->order_details_count,

                    $this->csvSafe($pName),
                    $this->csvSafe($sku),
                    $this->csvSafe($catsStr),
                    $qty,

                    $this->csvSafe($customerLabel),

                    $this->moneyFmt($order->grand_total),
                    $this->labelize($order->delivery_status),
                    $this->labelize($order->payment_type),
                    $this->labelize($order->payment_status),

                    $this->csvSafe($shipCountry),
                    $this->csvSafe($shipState),
                    $this->csvSafe($shipCity),

                    $this->csvSafe($sName),
                    $this->csvSafe($sEmail),
                    $this->csvSafe($sPhone),
                    $this->csvSafe($this->oneLine($sAddr)),
                    $this->csvSafe($sZip),

                    optional($order->created_at)->format('Y-m-d H:i:s'),

                    $addInfo,

                    // Billing (from shipping JSON fallback)
                    $this->csvSafe($bName), $this->csvSafe($bEmail), $this->csvSafe($bPhone),
                    $this->csvSafe($bAddr1), $this->csvSafe($bAddr2),
                    $this->csvSafe($bCity), $this->csvSafe($bState), $this->csvSafe($bCountry), $this->csvSafe($bZip),

                    // Item-level extras
                    $this->labelize($od->delivery_status),
                    $this->labelize($od->payment_status),
                    $this->moneyFmt($od->tax ?? 0),
                    $this->moneyFmt($od->shipping_cost ?? 0),
                ]);

                if ((++$rows % 500) === 0) { fflush($out); }
            }
        }

        if ($rows === 0) {
            fputcsv($out, ['(no rows)']);
        }

        fclose($out);
    }, $filename, [
        'Content-Type'  => 'text/csv; charset=UTF-8',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Pragma'        => 'no-cache',
    ]);
}




/* ----------------- Helpers (same file) ----------------- */
/**
 * shipping_address JSON se full shipping fields nikalo:
 * name, email, phone, address, postal_code
 */
private function extractShippingFull($raw): array
{
    if ($raw === null || $raw === '') return ['','','','',''];

    $arr = is_array($raw) ? $raw : json_decode((string)$raw, true);
    if (!is_array($arr)) {
        $arr = json_decode(stripslashes((string)$raw), true);
    }
    if (!is_array($arr)) return ['','','','',''];

    $name   = (string)($arr['name']   ?? '');
    $email  = (string)($arr['email']  ?? '');
    $phone  = (string)($arr['phone']  ?? '');

    // address may be single string (multi-line). We'll keep as single field.
    $address = (string)($arr['address'] ?? ($arr['address1'] ?? ''));
    $postal  = (string)($arr['postal_code'] ?? ($arr['zip'] ?? ''));

    return [$name, $email, $phone, $address, $postal];
}

/**
 * Billing address ke common fields nikaalo.
 * Support keys: name,email,phone,address,address1,address2,city,state,state_name,country,country_name,postal_code,zip
 */
 
 
// new_orders row -> billing fields array
private function fromNewOrdersBilling($row): array
{
    // guard
    if (!$row) return ['','','','','','','','',''];

    $name  = trim(($row->billing_first_name ?? '').' '.($row->billing_last_name ?? ''));
    $email = (string)($row->billing_email ?? '');
    $phone = (string)($row->billing_phone ?? '');

    $addr1 = (string)($row->billing_address ?? '');
    $addr2 = (string)($row->billing_apartment ?? '');

    $city    = (string)($row->billing_city ?? '');
    $state   = ''; // new_orders me state field nahi; agar ho to map kar dena
    $country = (string)($row->billing_country ?? '');
    $zip     = ''; // new_orders me postal/zip nahi; agar ho to map kar dena

    return [$name,$email,$phone,$addr1,$addr2,$city,$state,$country,$zip];
}

// shipping_address JSON se billing-like extract (fallback)
private function billingFromShippingJSON($raw): array
{
    if ($raw === null || $raw === '') {
        return ['','','','','','','','',''];
    }
    $arr = is_array($raw) ? $raw : json_decode((string)$raw, true);
    if (!is_array($arr)) {
        $arr = json_decode(stripslashes((string)$raw), true);
    }
    if (!is_array($arr)) return ['','','','','','','','',''];

    $name  = (string)($arr['name']  ?? '');
    $email = (string)($arr['email'] ?? '');
    $phone = (string)($arr['phone'] ?? '');

    // address: `address` may contain multi-line
    $addr = (string)($arr['address'] ?? '');
    // split into 2 lines (rough)
    $addr1 = trim(preg_split('/\R+/', $addr)[0] ?? $addr);
    $addr2 = trim(substr($addr, strlen($addr1))) ?: '';

    $city    = (string)($arr['city']        ?? ($arr['town'] ?? ''));
    $state   = (string)($arr['state']       ?? ($arr['state_name'] ?? ''));
    $country = (string)($arr['country']     ?? ($arr['country_name'] ?? ''));
    $zip     = (string)($arr['postal_code'] ?? ($arr['zip'] ?? ''));

    return [$name,$email,$phone,$addr1,$addr2,$city,$state,$country,$zip];
}



// Extract shipping country/state/city from shipping_address JSON.
private function extractAddressParts($raw): array
{
    if ($raw === null || $raw === '') return ['-','-','-'];
    $arr = is_array($raw) ? $raw : json_decode((string)$raw, true);
    if (!is_array($arr)) {
        $arr = json_decode(stripslashes((string)$raw), true);
    }
    if (!is_array($arr)) return ['-','-','-'];

    $country = trim((string)($arr['country'] ?? ($arr['country_name'] ?? '')));
    $state   = trim((string)($arr['state']   ?? ($arr['state_name']  ?? '')));
    $city    = trim((string)($arr['city']    ?? ($arr['town']        ?? '')));

    return [
        $country !== '' ? $country : '-',
        $state   !== '' ? $state   : '-',
        $city    !== '' ? $city    : '-',
    ];
}

private function resolveUserNameEmail($user, $shippingRaw): array
{
    $name  = $user?->name  ?? '';
    $email = $user?->email ?? '';

    if ($name === '' || $email === '') {
        [$shipName, $shipEmail] = $this->extractNameEmail($shippingRaw);
        if ($name === ''  && $shipName  !== '') $name  = $shipName;
        if ($email === '' && $shipEmail !== '') $email = $shipEmail;
    }
    return [$name, $email];
}

private function extractNameEmail($raw): array
{
    if ($raw === null || $raw === '') return ['', ''];
    $arr = is_array($raw) ? $raw : json_decode((string)$raw, true);
    if (!is_array($arr)) $arr = json_decode(stripslashes((string)$raw), true);
    if (!is_array($arr)) return ['', ''];

    $name  = $arr['name']  ?? ($arr['customer']['name']  ?? '');
    $email = $arr['email'] ?? ($arr['customer']['email'] ?? '');
    return [ (string)$name, (string)$email ];
}

private function csvSafe($v): string {
    if ($v === null) return '';
    $v = (string)$v; $t = ltrim($v);
    return ($t !== '' && in_array($t[0], ['=','+','-','@'])) ? "'".$v : $v;
}
private function moneyFmt($v): string {
    if ($v === null) return '0.00';
    return number_format((float)$v, 2, '.', '');
}
private function labelize($v): string {
    return $v ? ucfirst(str_replace('_',' ', (string)$v)) : '';
}
private function oneLine(string $s): string {
    // additional_info ko single-line bana do
    $s = strip_tags($s);
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}

/**
 * Readable variation normalize
 * Examples:
 *  - "Size-L,Color-Red"  => "Size-L | Color-Red"
 *  - {"Size":"L","Color":"Red"} => "Size-L | Color-Red"
 */
private function normalizeVariation($variation): string
{
    if (empty($variation)) return '';
    if (is_array($variation)) {
        $parts = [];
        foreach ($variation as $k => $v) {
            $k = is_int($k) ? '' : (string)$k;
            $v = (string)$v;
            $parts[] = $k !== '' ? "{$k}-{$v}" : $v;
        }
        return implode(' | ', array_filter($parts));
    }
    $str = (string)$variation;

    // Try JSON
    $arr = json_decode($str, true);
    if (is_array($arr)) return $this->normalizeVariation($arr);

    // Fallback CSV -> pipes
    return str_replace(',', ' | ', $str);
}

/**
 * Choose SKU by matching variant when possible
 */
private function skuFromOrderDetail($od): string
{
    $p = $od->product;
    if (!$p) return '';

    $variant = $this->normalizeVariantKey($od->variation); // like "L-Red" or "Size-L-Color-Red"
    if ($variant !== '') {
        // Try exact match by stocks->variant
        $match = $p->stocks->firstWhere('variant', $variant);
        if ($match && !empty($match->sku)) return (string)$match->sku;

        // Loose contains match (sometimes stored as "L-Red", sometimes "Size-L-Color-Red")
        foreach ($p->stocks as $st) {
            if (!empty($st->variant) && stripos($st->variant, $variant) !== false && !empty($st->sku)) {
                return (string)$st->sku;
            }
        }
    }

    // Fallback: first sku
    return (string)($p->stocks->first()->sku ?? '');
}

private function normalizeVariantKey($variation): string
{
    if (empty($variation)) return '';
    if (is_array($variation)) {
        $vals = [];
        foreach ($variation as $k => $v) {
            $vals[] = trim((string)$v);
        }
        return implode('-', array_filter($vals));
    }
    // "Size-L,Color-Red" -> "Size-L-Color-Red" and also simpler "L-Red"
    $raw = str_replace(',', '-', (string)$variation);
    $simple = preg_replace('/(?:Size-|Color-|Option-)/i', '', $raw);
    $simple = preg_replace('/\s+/', '', $simple);
    return trim($simple, '-');
}


    // All Orders
    public function all_orders(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = User::where('user_type', 'admin')->first()->id;


        if (
            Route::currentRouteName() == 'inhouse_orders.index' &&
            Auth::user()->can('view_inhouse_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'seller_orders.index' &&
            Auth::user()->can('view_seller_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'pick_up_point.index' &&
            Auth::user()->can('view_pickup_point_orders')
        ) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        } else if (
            Route::currentRouteName() == 'all_orders.index' &&
            Auth::user()->can('view_all_orders')
        ) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
        } else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $deliveryCity = '';
        if (is_object($order_shipping_address)) {
            $deliveryCity = $order_shipping_address->city ?? '';
        } elseif (is_array($order_shipping_address)) {
            $deliveryCity = $order_shipping_address['city'] ?? '';
        }

        $delivery_boys = User::when($deliveryCity, function ($query) use ($deliveryCity) {
                return $query->where('city', $deliveryCity);
            })
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();

        // $order = Order::findOrFail(decrypt($$order->id));
        // $done = $this->convertToGivenOrderArray($order);
        // return $done;
        return view('backend.sales.show', compact('order', 'delivery_boys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            if (Auth::check()) {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
    } else {
        $carts = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();
    }

    if ($carts->isEmpty()) {
        flash(translate('Your cart is empty'))->warning();
        return redirect()->route('home');
    }

    $shippingAddress = [];

    // 🟢 Logged-in user
    if (Auth::check()) {
        // Check if logged-in user selected pickup mode
        $loggedPickupInfo = session()->get('logged_pickup_info');
        $isPickupMode = $loggedPickupInfo && (($loggedPickupInfo['mode'] ?? null) === 'pickup');

        if ($isPickupMode) {
            // Use pickup info from session
            $shippingAddress = $loggedPickupInfo;
        } else {
            // Use address from database
            $address = Address::where('id', $carts[0]['address_id'])->first();

            if ($address) {
                $shippingAddress = [
                    'name'        => Auth::user()->name,
                    'email'       => Auth::user()->email,
                    'address'     => $address->address,
                    'country'     => $address->country->name ?? null,
                    'state'       => $address->state->name ?? null,
                    'city'        => $address?->city?->name,
                    'postal_code' => $address->postal_code,
                    'phone'       => $address->phone,
                    'mode'        => 'shipping',
                ];

                if ($address->latitude || $address->longitude) {
                    $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
                }
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::id();
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->grand_total = 0;
        $combined_order->save();
    } 
    // 🔵 Guest user
    else {
        $guest_address = session()->get('guest_shipping_address');

        if ($guest_address) {
            $shippingAddress = $guest_address;
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = null; // guest ke liye NULL hi rakho
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->grand_total = 0;
        $combined_order->save();
    }

    $pickupMode = ($shippingAddress['mode'] ?? null) === 'pickup';

    // Separate course items from product items
    $courseItems = [];
    $productItems = [];
    
    foreach ($carts as $cartItem) {
        $itemType = is_object($cartItem) ? ($cartItem->item_type ?? 'product') : ($cartItem['item_type'] ?? 'product');
        
        if ($itemType === 'course') {
            $courseItems[] = $cartItem;
        } else {
            $productItems[] = $cartItem;
        }
    }
    
    // Process course items first (require login)
    if (!empty($courseItems) && !Auth::check()) {
        flash(translate('Please login to purchase courses'))->warning();
        return redirect()->route('user.login');
    }
    
    if (!empty($courseItems) && Auth::check()) {
        // Get admin user for course orders
        $adminUser = \App\Models\User::where('user_type', 'admin')->first();
        if (!$adminUser) {
            // Fallback to first user if no admin found
            $adminUser = \App\Models\User::first();
        }
        
        // Create order for courses
        $courseOrder = new Order;
        $courseOrder->combined_order_id = $combined_order->id;
        $courseOrder->user_id = Auth::id();
        $courseOrder->seller_id = $adminUser ? $adminUser->id : 1;
        $courseOrder->shipping_address = $combined_order->shipping_address;
        $courseOrder->payment_type = $request->payment_option;
        $courseOrder->delivery_viewed = '0';
        $courseOrder->payment_status_viewed = '0';
        $courseOrder->code = date('Ymd-His') . rand(10, 99);
        $courseOrder->date = strtotime('now');
        $courseOrder->shipping_type = 'digital'; // Courses are digital
        $courseOrder->save();
        
        $courseSubtotal = 0;
        $courseTax = 0;
        
        foreach ($courseItems as $cartItem) {
            $cartItemArray = is_object($cartItem) ? $cartItem->toArray() : $cartItem;
            
            $courseId = $cartItemArray['course_id'] ?? null;
            if (!$courseId) {
                continue;
            }
            
            $course = \App\Models\Course::find($courseId);
            if (!$course) {
                continue;
            }
            
            $price = $cartItemArray['price'] ?? 0;
            $quantity = $cartItemArray['quantity'] ?? 1;
            $tax = $cartItemArray['tax'] ?? 0;
            
            $courseSubtotal += $price * $quantity;
            $courseTax += $tax * $quantity;
            
            // Create order detail for course
            $order_detail = new OrderDetail;
            $order_detail->order_id = $courseOrder->id;
            $order_detail->seller_id = $adminUser ? $adminUser->id : 1;
            $order_detail->product_id = null; // Courses don't have product_id
            $order_detail->course_id = $courseId;
            $order_detail->course_schedule_id = $cartItemArray['course_schedule_id'] ?? null;
            $order_detail->item_type = 'course';
            $order_detail->variation = $cartItemArray['variation'] ?? null;
            $order_detail->price = $price;
            $order_detail->tax = $tax;
            $order_detail->shipping_type = 'digital';
            $order_detail->shipping_cost = 0;
            $order_detail->quantity = $quantity;
            
            // Store course metadata
            $variationData = $cartItemArray['variation'] ? json_decode($cartItemArray['variation'], true) : [];
            $order_detail->course_metadata = json_encode([
                'selected_date' => $variationData['selected_date'] ?? null,
                'selected_time' => $variationData['selected_time'] ?? null,
                'selected_level' => $variationData['selected_level'] ?? null,
            ]);
            $order_detail->save();
            
            // Create CoursePurchase record after order_detail is saved
            $coursePurchase = \App\Models\CoursePurchase::create([
                'user_id' => Auth::id(),
                'course_id' => $courseId,
                'course_schedule_id' => $cartItemArray['course_schedule_id'] ?? null,
                'order_id' => $courseOrder->id,
                'order_detail_id' => $order_detail->id,
                'amount' => $price,
                'selected_date' => $variationData['selected_date'] ?? null,
                'selected_time' => $variationData['selected_time'] ?? null,
                'selected_level' => $variationData['selected_level'] ?? null,
                'payment_status' => 'pending',
                'code' => 'CP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            ]);
        }
        
        $courseOrder->grand_total = $courseSubtotal + $courseTax;
        $courseOrder->save();
        
        // Update combined order total
        $combined_order->grand_total += $courseOrder->grand_total;
        $combined_order->save();
    }

        // Process product items (existing logic)
        $seller_products = array();
        foreach ($productItems as $cartItem) {
            // Handle both object and array access
            $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
            if (!$productId) {
                continue; // Skip if no product_id
            }
            
            $product = Product::find($productId);
            if (!$product) {
                continue; // Skip if product not found
            }
            
            $product_ids = array();
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            
if (Auth::check()) {
    $order->user_id = Auth::id(); // Logged-in user
} else {
    $order->user_id = $request->session()->get('temp_user_id'); // Guest user ka temp id
    // agar DB me `user_id` column NULL allow karta hai to:
    // $order->user_id = null;
}

            $order->shipping_address = $combined_order->shipping_address;

            $order->additional_info = $request->additional_info;

            if ($pickupMode) {
                $order->shipping_type = 'pickup_point';
                $firstCart = $seller_product[0] ?? null;
                $order->pickup_point_id = $firstCart['pickup_point'] ?? null;
            }

            // $order->shipping_type = $carts[0]['shipping_type'];
            // if ($carts[0]['shipping_type'] == 'pickup_point') {
            //     $order->pickup_point_id = $cartItem['pickup_point'];
            // }
            // if ($carts[0]['shipping_type'] == 'carrier') {
            //     $order->carrier_id = $cartItem['carrier_id'];
            // }

            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
   // --- inside "foreach ($seller_product as $cartItem) { ... }" --- //

                // Handle both object and array access
                $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
                if (!$productId) {
                    continue; // Skip if no product_id
                }
                
$product = Product::find($productId);
if (!$product) {
    // Product hi nahi mila -> cart se hatao aur continue/abort
    flash(translate('Product not found'))->warning();
    $order->delete();
    return redirect()->route('cart')->send();
}

                // Handle both object and array access for cart item properties
                $itemPrice = is_object($cartItem) ? $cartItem->price : ($cartItem['price'] ?? 0);
                $itemQuantity = is_object($cartItem) ? $cartItem->quantity : ($cartItem['quantity'] ?? 1);
                $itemDiscount = is_object($cartItem) ? ($cartItem->discount ?? 0) : ($cartItem['discount'] ?? 0);
                $itemVariation = is_object($cartItem) ? ($cartItem->variation ?? '') : ($cartItem['variation'] ?? '');
                
$subtotal       += $itemPrice * $itemQuantity;
$tax            += cart_product_tax($cartItem, $product, false) * $itemQuantity;
$coupon_discount += $itemDiscount;

$rawVariation   = (string) $itemVariation;
$variant        = trim($rawVariation);

// --- STOCK FETCH (robust) ---
$product_stock = null;

// 1) Try exact variant match if variant string exists
if ($variant !== '') {
    $product_stock = ProductStock::where('product_id', $product->id)
        ->where('variant', $variant)
        ->first();
}

// 2) If not found and product is NOT digital, try sensible fallbacks
if ($product->digital != 1 && !$product_stock) {
    // (a) Try empty variant row (simple products often use '')
    $product_stock = ProductStock::where('product_id', $product->id)
        ->where(function ($q) use ($variant) {
            $q->where('variant', '')
              ->orWhereNull('variant');
        })
        ->first();

    // (b) As a last resort, pick the first stock row (if any)
    if (!$product_stock) {
        $product_stock = ProductStock::where('product_id', $product->id)->first();
    }
}

// 3) If still no stock for a non-digital product => out of stock
if ($product->digital != 1 && (!$product_stock || $product_stock->qty === null)) {
    flash(translate('Stock not available for ') . $product->getTranslation('name'))->warning();
    $order->delete();
    return redirect()->route('cart')->send();
}

// 4) Quantity check for physical items
if ($product->digital != 1) {
    $availableQty = (int) $product_stock->qty;
    if ($itemQuantity > $availableQty) {
        flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
        $order->delete();
        return redirect()->route('cart')->send();
    }

    // Reduce stock
    $product_stock->qty = max(0, $availableQty - (int)$itemQuantity);
    $product_stock->save();
}

// --- proceed to create OrderDetail normally ---
$order_detail = new OrderDetail;
$order_detail->order_id   = $order->id;
$order_detail->seller_id  = $product->user_id;
$order_detail->product_id = $product->id;
$order_detail->variation  = $variant;
                $itemShippingType = is_object($cartItem) ? ($cartItem->shipping_type ?? null) : ($cartItem['shipping_type'] ?? null);
                $itemProductReferralCode = is_object($cartItem) ? ($cartItem->product_referral_code ?? null) : ($cartItem['product_referral_code'] ?? null);
                $itemShippingCost = is_object($cartItem) ? ($cartItem->shipping_cost ?? 0) : ($cartItem['shipping_cost'] ?? 0);
                
$order_detail->price      = $itemPrice * $itemQuantity;
$order_detail->tax        = cart_product_tax($cartItem, $product, false) * $itemQuantity;
$order_detail->shipping_type = $pickupMode ? 'pickup_point' : $itemShippingType;
$order_detail->product_referral_code = $itemProductReferralCode;
$order_detail->shipping_cost = $pickupMode ? 0 : $itemShippingCost;
$shipping += (float)$order_detail->shipping_cost;
$order_detail->quantity   = $itemQuantity;

if (addon_is_activated('club_point')) {
    $order_detail->earn_point = $product->earn_point;
}

$order_detail->save();

$product->num_of_sale += $itemQuantity;
$product->save();


                $order->seller_id = $product->user_id;
                $order->shipping_type = $itemShippingType;
                
                if ($pickupMode || $itemShippingType == 'pickup_point') {
                    $order->pickup_point_id = is_object($cartItem) ? ($cartItem->pickup_point ?? null) : ($cartItem['pickup_point'] ?? null);
                }
                if (!$pickupMode && $itemShippingType == 'carrier') {
                    $order->carrier_id = is_object($cartItem) ? ($cartItem->carrier_id ?? null) : ($cartItem['carrier_id'] ?? null);
                }

                if ($product->added_by == 'seller' && $product->user->seller != null) {
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $itemQuantity;
                    $seller->save();
                }

                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if ($seller_product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }

            $combined_order->grand_total += $order->grand_total;

            $order->save();
        }

        $combined_order->save();

        foreach($combined_order->orders as $order){
            NotificationUtility::sendOrderPlacedNotification($order);
        }

        $request->session()->put('combined_order_id', $combined_order->id);


        // add into shopify;
        
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }

    private function convertToGivenOrderArray($laravelArray)
    {
        // Convert laravelArray to the given order array format
        $order = [
            "id" => $laravelArray['id'] ?? null,
            "combined_order_id" => $laravelArray['combined_order_id'] ?? null,
            "user_id" => $laravelArray['user_id'] ?? null,
            "guest_id" => $laravelArray['guest_id'] ?? null,
            "seller_id" => $laravelArray['seller_id'] ?? null,
            "shipping_address" => $laravelArray['shipping_address'] ?? null,
            "additional_info" => $laravelArray['additional_info'] ?? null,
            "shipping_type" => $laravelArray['shipping_type'] ?? null,
            "order_from" => $laravelArray['order_from'] ?? null,
            "pickup_point_id" => $laravelArray['pickup_point_id'] ?? null,
            "carrier_id" => $laravelArray['carrier_id'] ?? null,
            "delivery_status" => $laravelArray['delivery_status'] ?? null,
            "payment_type" => $laravelArray['payment_type'] ?? null,
            "payment_status" => $laravelArray['payment_status'] ?? null,
            "payment_details" => $laravelArray['payment_details'] ?? null,
            "grand_total" => $laravelArray['grand_total'] ?? null,
            "coupon_discount" => $laravelArray['coupon_discount'] ?? null,
            "code" => $laravelArray['code'] ?? null,
            "tracking_code" => $laravelArray['tracking_code'] ?? null,
            "date" => $laravelArray['date'] ?? null,
            "viewed" => $laravelArray['viewed'] ?? null,
            "delivery_viewed" => $laravelArray['delivery_viewed'] ?? null,
            "payment_status_viewed" => $laravelArray['payment_status_viewed'] ?? null,
            "commission_calculated" => $laravelArray['commission_calculated'] ?? null,
            "created_at" => $laravelArray['created_at'] ?? null,
            "updated_at" => $laravelArray['updated_at'] ?? null,
            "line_items" => $laravelArray['line_items'] ?? [],
            "transactions" => $laravelArray['transactions'] ?? [],
            "total_tax" => $laravelArray['total_tax'] ?? null,
            "currency" => $laravelArray['currency'] ?? null,
            "customer" => $laravelArray['customer'] ?? [],
            "tags" => $laravelArray['tags'] ?? null,
            "tax_exemptions" => $laravelArray['tax_exemptions'] ?? [],
            "admin_graphql_api_id" => $laravelArray['admin_graphql_api_id'] ?? null,
        ];

        return $order;
    }
}
