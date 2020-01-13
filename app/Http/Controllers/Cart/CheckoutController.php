<?php


namespace App\Http\Controllers\Cart;


use App\Http\Controllers\Controller;
use App\Classes\Cart\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemOption;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Mollie\Api\MollieApiClient;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    private $mollie;

    public function __construct()
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey('test_US2NxBddK7fzrM78DwyWjdTSUhA3Qz');
    }

    public function index()
    {
        $address = Address::where('hash', Cookie::get('address'))->first();
        return view('cart.checkout', [
            'cart' => Cart::all(),
            'address' => $address
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:256',
            'phone' => 'nullable|email|max:256',
            'first-name' => 'required|string|max:256',
            'last-name' => 'required|string|max:256',
            'address' => 'required|string|max:512',
            'address_extra' => 'nullable|string|max:512',
            'city' => 'required|string|max:512',
            'zip' => 'required|string|max:16',
            'country' => [
                'required',
                'in:' . implode(',', [
                    "Bangladesh", "Belgium", "Burkina Faso", "Bulgaria", "Bosnia and Herzegovina", "Barbados", "Wallis and Futuna", "Saint Barthelemy", "Bermuda", "Brunei", "Bolivia", "Bahrain", "Burundi", "Benin", "Bhutan", "Jamaica", "Bouvet Island", "Botswana", "Samoa", "Bonaire, Saint Eustatius and Saba ", "Brazil", "Bahamas", "Jersey", "Belarus", "Belize", "Russia", "Rwanda", "Serbia", "East Timor", "Reunion", "Turkmenistan", "Tajikistan", "Romania", "Tokelau", "Guinea-Bissau", "Guam", "Guatemala", "South Georgia and the South Sandwich Islands", "Greece", "Equatorial Guinea", "Guadeloupe", "Japan", "Guyana", "Guernsey", "French Guiana", "Georgia", "Grenada", "United Kingdom", "Gabon", "El Salvador", "Guinea", "Gambia", "Greenland", "Gibraltar", "Ghana", "Oman", "Tunisia", "Jordan", "Croatia", "Haiti", "Hungary", "Hong Kong", "Honduras", "Heard Island and McDonald Islands", "Venezuela", "Puerto Rico", "Palestinian Territory", "Palau", "Portugal", "Svalbard and Jan Mayen", "Paraguay", "Iraq", "Panama", "French Polynesia", "Papua New Guinea", "Peru", "Pakistan", "Philippines", "Pitcairn", "Poland", "Saint Pierre and Miquelon", "Zambia", "Western Sahara", "Estonia", "Egypt", "South Africa", "Ecuador", "Italy", "Vietnam", "Solomon Islands", "Ethiopia", "Somalia", "Zimbabwe", "Saudi Arabia", "Spain", "Eritrea", "Montenegro", "Moldova", "Madagascar", "Saint Martin", "Morocco", "Monaco", "Uzbekistan", "Myanmar", "Mali", "Macao", "Mongolia", "Marshall Islands", "Macedonia", "Mauritius", "Malta", "Malawi", "Maldives", "Martinique", "Northern Mariana Islands", "Montserrat", "Mauritania", "Isle of Man", "Uganda", "Tanzania", "Malaysia", "Mexico", "Israel", "France", "British Indian Ocean Territory", "Saint Helena", "Finland", "Fiji", "Falkland Islands", "Micronesia", "Faroe Islands", "Nicaragua", "The Netherlands", "Norway", "Namibia", "Vanuatu", "New Caledonia", "Niger", "Norfolk Island", "Nigeria", "New Zealand", "Nepal", "Nauru", "Niue", "Cook Islands", "Kosovo", "Ivory Coast", "Switzerland", "Colombia", "China", "Cameroon", "Chile", "Cocos Islands", "Canada", "Republic of the Congo", "Central African Republic", "Democratic Republic of the Congo", "Czech Republic", "Cyprus", "Christmas Island", "Costa Rica", "Curacao", "Cape Verde", "Cuba", "Swaziland", "Syria", "Sint Maarten", "Kyrgyzstan", "Kenya", "South Sudan", "Suriname", "Kiribati", "Cambodia", "Saint Kitts and Nevis", "Comoros", "Sao Tome and Principe", "Slovakia", "South Korea", "Slovenia", "Kuwait", "Senegal", "San Marino", "Sierra Leone", "Seychelles", "Kazakhstan", "Cayman Islands", "Singapore", "Sweden", "Sudan", "Dominican Republic", "Dominica", "Djibouti", "Denmark", "British Virgin Islands", "Germany", "Yemen", "Algeria", "United States", "Uruguay", "Mayotte", "United States Minor Outlying Islands", "Lebanon", "Saint Lucia", "Laos", "Tuvalu", "Taiwan", "Trinidad and Tobago", "Turkey", "Sri Lanka", "Liechtenstein", "Latvia", "Tonga", "Lithuania", "Luxembourg", "Liberia", "Lesotho", "Thailand", "French Southern Territories", "Togo", "Chad", "Turks and Caicos Islands", "Libya", "Vatican", "Saint Vincent and the Grenadines", "United Arab Emirates", "Andorra", "Antigua and Barbuda", "Afghanistan", "Anguilla", "U.S. Virgin Islands", "Iceland", "Iran", "Armenia", "Albania", "Angola", "Antarctica", "American Samoa", "Argentina", "Australia", "Austria", "Aruba", "India", "Aland Islands", "Azerbaijan", "Ireland", "Indonesia", "Ukraine", "Qatar", "Mozambique",
                ])
            ]
        ]);

        $cookie = Cookie::get('address');
        $address = Address::where('hash', $cookie)->first();

        if ($address) {
            $address->update([
                'first_name' => $request->input('first-name'),
                'last_name' => $request->input('last-name'),
                'address' => $request->input('address'),
                'address_extra' => $request->input('address-extra'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country'),
                'type' => 0, // shippping
                'hash' => Str::random(32)
            ]);
        } else {
            $address = Address::create([
                'first_name' => $request->input('first-name'),
                'last_name' => $request->input('last-name'),
                'address' => $request->input('address'),
                'address_extra' => $request->input('address-extra'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country'),
                'type' => 0, // shippping
                'hash' => Str::random(32)
            ]);
        }

        Cookie::queue('address', $address->hash, 241920);

        Session::put('contact', [
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);


        return redirect()->route('cart.checkout.shipping');
    }

    public function shipping()
    {
        if (!Session::has('contact') || Session::has('adddress')) return redirect()->route('cart.checkout');
        $address = Address::where('hash', Cookie::get('address'))->first();
        if (!$address) return redirect()->route('cart.checkout');

        return view('cart.shipping', [
            'cart' => Cart::all(),
            'address' => $address
        ]);
    }

    public function payment()
    {
        if (!Session::has('contact') || Session::has('adddress')) return redirect()->route('cart.checkout');
        $address = Address::where('hash', Cookie::get('address'))->first();
        if (!$address) return redirect()->route('cart.checkout');

        return view('cart.payment', [
            'cart' => Cart::all(),
            'address' => $address
        ]);
    }

    public function handle(Request $request)
    {
        if (!Session::has('contact') || Session::has('adddress')) return redirect()->route('cart.checkout');
        $request->validate([
            'first-name' => 'required_if:billing-address,custom|string|max:256',
            'last-name' => 'required_if:billing-address,custom|string|max:256',
            'address' => 'required_if:billing-address,custom|string|max:512',
            'address_extra' => 'nullable|string|max:512',
            'city' => 'required_if:billing-address,custom|string|max:512',
            'zip' => 'required_if:billing-address,custom|string|max:16',
            'country' => [
                'required_if:billing-address,custom',
                'in:' . implode(',', [
                    "Bangladesh", "Belgium", "Burkina Faso", "Bulgaria", "Bosnia and Herzegovina", "Barbados", "Wallis and Futuna", "Saint Barthelemy", "Bermuda", "Brunei", "Bolivia", "Bahrain", "Burundi", "Benin", "Bhutan", "Jamaica", "Bouvet Island", "Botswana", "Samoa", "Bonaire, Saint Eustatius and Saba ", "Brazil", "Bahamas", "Jersey", "Belarus", "Belize", "Russia", "Rwanda", "Serbia", "East Timor", "Reunion", "Turkmenistan", "Tajikistan", "Romania", "Tokelau", "Guinea-Bissau", "Guam", "Guatemala", "South Georgia and the South Sandwich Islands", "Greece", "Equatorial Guinea", "Guadeloupe", "Japan", "Guyana", "Guernsey", "French Guiana", "Georgia", "Grenada", "United Kingdom", "Gabon", "El Salvador", "Guinea", "Gambia", "Greenland", "Gibraltar", "Ghana", "Oman", "Tunisia", "Jordan", "Croatia", "Haiti", "Hungary", "Hong Kong", "Honduras", "Heard Island and McDonald Islands", "Venezuela", "Puerto Rico", "Palestinian Territory", "Palau", "Portugal", "Svalbard and Jan Mayen", "Paraguay", "Iraq", "Panama", "French Polynesia", "Papua New Guinea", "Peru", "Pakistan", "Philippines", "Pitcairn", "Poland", "Saint Pierre and Miquelon", "Zambia", "Western Sahara", "Estonia", "Egypt", "South Africa", "Ecuador", "Italy", "Vietnam", "Solomon Islands", "Ethiopia", "Somalia", "Zimbabwe", "Saudi Arabia", "Spain", "Eritrea", "Montenegro", "Moldova", "Madagascar", "Saint Martin", "Morocco", "Monaco", "Uzbekistan", "Myanmar", "Mali", "Macao", "Mongolia", "Marshall Islands", "Macedonia", "Mauritius", "Malta", "Malawi", "Maldives", "Martinique", "Northern Mariana Islands", "Montserrat", "Mauritania", "Isle of Man", "Uganda", "Tanzania", "Malaysia", "Mexico", "Israel", "France", "British Indian Ocean Territory", "Saint Helena", "Finland", "Fiji", "Falkland Islands", "Micronesia", "Faroe Islands", "Nicaragua", "The Netherlands", "Norway", "Namibia", "Vanuatu", "New Caledonia", "Niger", "Norfolk Island", "Nigeria", "New Zealand", "Nepal", "Nauru", "Niue", "Cook Islands", "Kosovo", "Ivory Coast", "Switzerland", "Colombia", "China", "Cameroon", "Chile", "Cocos Islands", "Canada", "Republic of the Congo", "Central African Republic", "Democratic Republic of the Congo", "Czech Republic", "Cyprus", "Christmas Island", "Costa Rica", "Curacao", "Cape Verde", "Cuba", "Swaziland", "Syria", "Sint Maarten", "Kyrgyzstan", "Kenya", "South Sudan", "Suriname", "Kiribati", "Cambodia", "Saint Kitts and Nevis", "Comoros", "Sao Tome and Principe", "Slovakia", "South Korea", "Slovenia", "Kuwait", "Senegal", "San Marino", "Sierra Leone", "Seychelles", "Kazakhstan", "Cayman Islands", "Singapore", "Sweden", "Sudan", "Dominican Republic", "Dominica", "Djibouti", "Denmark", "British Virgin Islands", "Germany", "Yemen", "Algeria", "United States", "Uruguay", "Mayotte", "United States Minor Outlying Islands", "Lebanon", "Saint Lucia", "Laos", "Tuvalu", "Taiwan", "Trinidad and Tobago", "Turkey", "Sri Lanka", "Liechtenstein", "Latvia", "Tonga", "Lithuania", "Luxembourg", "Liberia", "Lesotho", "Thailand", "French Southern Territories", "Togo", "Chad", "Turks and Caicos Islands", "Libya", "Vatican", "Saint Vincent and the Grenadines", "United Arab Emirates", "Andorra", "Antigua and Barbuda", "Afghanistan", "Anguilla", "U.S. Virgin Islands", "Iceland", "Iran", "Armenia", "Albania", "Angola", "Antarctica", "American Samoa", "Argentina", "Australia", "Austria", "Aruba", "India", "Aland Islands", "Azerbaijan", "Ireland", "Indonesia", "Ukraine", "Qatar", "Mozambique",
                ])
            ]
        ]);

        $billingAddress = null;
        $shippingAddress = Address::where('hash', Cookie::get('address'))->first();

        if ($request->input('billing-address') === 'custom') {
            $billingAddress = Address::create([
                'first_name' => $request->input('first-name'),
                'last_name' => $request->input('last-name'),
                'address' => $request->input('address'),
                'address_extra' => $request->input('address-extra'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country'),
                'type' => 1, // billing
                'hash' => Str::random(32)
            ]);
        } else {
            $billingAddress = $shippingAddress;
        }

        if (!$billingAddress || !$shippingAddress) return redirect()->route('cart.checkout');

        $order = Order::create([
            'address_id' => $shippingAddress->id,
            'email' => Session::get('contact')['email'],
            'phone' => Session::get('contact')['phone'],
            'status' => 0,
            'hash' => md5(Carbon::now()->timestamp . $billingAddress->id)
        ]);

        $total = Cart::subTotal();

        foreach (Cart::all() as $item) {
            $price = Cart::itemDiscountPrice($item->hash);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'quantity' => $item->quantity,
                'price' => $price
            ]);

            foreach ($item->options as $option) {
                OrderItemOption::create([
                    'order_item_id' => $orderItem->id,
                    'name' => $option->title,
                    'value' => $option->value,
                    'increment' => $option->increment
                ]);
            }
        }

        if ($request->input('payment-method') === 'creditcard') {
            $total += ($total / 100 * 2);
        }

//        $payment = $this->mollie->payments->create([
//            'amount' => [
//                'currency' => 'EUR',
//                'value' => number_format($total, 2, '.', ',')
//            ],
//            'description' => "Payment for order {$order->hash} at " . config('app.name'),
//            'redirectUrl' => route('order.view', $order->hash);,
//            'method' => $request->input('payment-method'),
//            'metadata' => [
//                'order_id' => $order->id,
//                'address_id' => $billingAddress->id,
//            ]
//        ]);

//        return redirect($payment->getCheckoutUrl());

        Payment::create([
            'hash' => md5(Carbon::now()->timestamp . $order->id),
            'amount' => $total,
            'method' => $request->input('payment-method'),
            'order_id' => $order->id,
            'address_id' => $billingAddress->id,
        ]);

        return redirect()->route('order.view', $order->hash);
    }
}
