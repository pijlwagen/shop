<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\UserAddress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index()
    {
        $user = User::with(['address'])->find(Auth::user()->id);
        return view('account.index', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'nullable|same:confirm-password',
            'confirm-password' => 'nullable|same:password',
        ]);

        $user = Auth::user();

        if ($request->input('password')) {
            $user->update([
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('confirm-password')),
            ]);
        } else {
            $user->update([
                'email' => $request->input('email'),
            ]);
        }

        return back();
    }

    public function orders()
    {
        $user = User::with(['orders' => function ($query) {
            return $query->with(['items', 'payment']);
        }])->find(Auth::user()->id);

        return view('account.orders', [
            'user' => $user,
        ]);
    }

    public function address(Request $request)
    {
        $request->validate([
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

        $user = User::with(['address'])->find(Auth::user()->id);
        if ($user->address) {
            $address = $user->address->update([
                'first_name' => $request->input('first-name'),
                'last_name' => $request->input('last-name'),
                'address' => $request->input('address'),
                'address_extra' => $request->input('address-extra'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country'),
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

        if (!UserAddress::where('user_id', $user->id)->first()) {
            UserAddress::create([
                'address_id' => $address->id,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->back();
    }
}
