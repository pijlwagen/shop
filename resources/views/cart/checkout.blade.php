@extends('layouts.app')

@section('content')
    <div class="container-fluid px-5">
        <h1 style="font-size: 1.75rem" id="checkout-step" class="mb-5">Checkout: Step 1 of 3</h1>
        <form action="{{ route('cart.checkout.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <h3 style="font-size: 1.25rem">Contact Information</h3>
                    <div class="form-group row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="email">E-Mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email', Session::get('contact')['email'] ?? Auth::user()->email ?? '') }}"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone">Phone <small class="text-muted">(optional)</small></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', Session::get('contact')['phone']) }}"
                                   class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <hr class="my-4">
                    <h3 style="font-size: 1.25rem">Shipping Address</h3>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 m-sm-0">
                            <label for="first-name">First name</label>
                            <input type="text" name="first-name" id="first-name" value="{{ old('first-name', $address->first_name ?? null) }}"
                                   class="form-control @error('first-name') is-invalid @enderror">
                            @error('first-name')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="last-name">Last name</label>
                            <input type="text" name="last-name" id="last-name" value="{{ old('last-name', $address->last_name ?? null) }}"
                                   class="form-control @error('last-name') is-invalid @enderror">
                            @error('last-name')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $address->address ?? null) }}"
                                   class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="address-details">Apartment / suite <small class="text-muted">(optional)</small></label>
                            <input type="text" name="address-details" id="address-details"
                                   value="{{ old('address-details', $address->address_extra ?? null) }}"
                                   class="form-control @error('address-details') is-invalid @enderror">
                            @error('address-details')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 mb-3 mb-sm-0">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? null) }}"
                                   class="form-control @error('city') is-invalid @enderror">
                            @error('city')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3 mb-sm-0">
                            <label for="province">Province / State / Region</label>
                            <input type="text" name="province" id="province" value="{{ old('province', $address->province ?? null) }}"
                                   class="form-control @error('province') is-invalid @enderror">
                            @error('province')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip">Postal code</label>
                            <input type="text" name="zip" id="zip" value="{{ old('zip', $address->zip ?? null) }}"
                                   class="form-control @error('zip') is-invalid @enderror">
                            @error('zip')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country', $address->country ?? null) }}"
                               class="form-control @error('country') is-invalid @enderror" autocomplete="off">
                        @error('country')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary float-right">Next step</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-primary float-left">Back to cart</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cart as $item)
                                    <tr>
                                        <td><a href="{{ $item->slug }}" target="_blank">{{ $item->name }}</a></td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>&euro;{{ number_format(Cart::itemDiscountPrice($item->hash), 2, ',', '.') }}</td>
                                        <td>&euro;{{ number_format(Cart::itemDiscountPrice($item->hash) * $item->quantity, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Sub total:</td>
                                    <td>&euro;{{ number_format(Cart::subTotal(), 2, ',', '.') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('js')
    <script src="{{ asset('libs/autocomplete.min.js') }}"></script>
    <script>
        var countries = {
            'Bangladesh': 'Bangladesh',
            'Belgium': 'Belgium',
            'Burkina Faso': 'Burkina Faso',
            'Bulgaria': 'Bulgaria',
            'Bosnia and Herzegovina': 'Bosnia and Herzegovina',
            'Barbados': 'Barbados',
            'Wallis and Futuna': 'Wallis and Futuna',
            'Saint Barthelemy': 'Saint Barthelemy',
            'Bermuda': 'Bermuda',
            'Brunei': 'Brunei',
            'Bolivia': 'Bolivia',
            'Bahrain': 'Bahrain',
            'Burundi': 'Burundi',
            'Benin': 'Benin',
            'Bhutan': 'Bhutan',
            'Jamaica': 'Jamaica',
            'Bouvet Island': 'Bouvet Island',
            'Botswana': 'Botswana',
            'Samoa': 'Samoa',
            'Bonaire, Saint Eustatius and Saba ': 'Bonaire, Saint Eustatius and Saba ',
            'Brazil': 'Brazil',
            'Bahamas': 'Bahamas',
            'Jersey': 'Jersey',
            'Belarus': 'Belarus',
            'Belize': 'Belize',
            'Russia': 'Russia',
            'Rwanda': 'Rwanda',
            'Serbia': 'Serbia',
            'East Timor': 'East Timor',
            'Reunion': 'Reunion',
            'Turkmenistan': 'Turkmenistan',
            'Tajikistan': 'Tajikistan',
            'Romania': 'Romania',
            'Tokelau': 'Tokelau',
            'Guinea-Bissau': 'Guinea-Bissau',
            'Guam': 'Guam',
            'Guatemala': 'Guatemala',
            'South Georgia and the South Sandwich Islands': 'South Georgia and the South Sandwich Islands',
            'Greece': 'Greece',
            'Equatorial Guinea': 'Equatorial Guinea',
            'Guadeloupe': 'Guadeloupe',
            'Japan': 'Japan',
            'Guyana': 'Guyana',
            'Guernsey': 'Guernsey',
            'French Guiana': 'French Guiana',
            'Georgia': 'Georgia',
            'Grenada': 'Grenada',
            'United Kingdom': 'United Kingdom',
            'Gabon': 'Gabon',
            'El Salvador': 'El Salvador',
            'Guinea': 'Guinea',
            'Gambia': 'Gambia',
            'Greenland': 'Greenland',
            'Gibraltar': 'Gibraltar',
            'Ghana': 'Ghana',
            'Oman': 'Oman',
            'Tunisia': 'Tunisia',
            'Jordan': 'Jordan',
            'Croatia': 'Croatia',
            'Haiti': 'Haiti',
            'Hungary': 'Hungary',
            'Hong Kong': 'Hong Kong',
            'Honduras': 'Honduras',
            'Heard Island and McDonald Islands': 'Heard Island and McDonald Islands',
            'Venezuela': 'Venezuela',
            'Puerto Rico': 'Puerto Rico',
            'Palestinian Territory': 'Palestinian Territory',
            'Palau': 'Palau',
            'Portugal': 'Portugal',
            'Svalbard and Jan Mayen': 'Svalbard and Jan Mayen',
            'Paraguay': 'Paraguay',
            'Iraq': 'Iraq',
            'Panama': 'Panama',
            'French Polynesia': 'French Polynesia',
            'Papua New Guinea': 'Papua New Guinea',
            'Peru': 'Peru',
            'Pakistan': 'Pakistan',
            'Philippines': 'Philippines',
            'Pitcairn': 'Pitcairn',
            'Poland': 'Poland',
            'Saint Pierre and Miquelon': 'Saint Pierre and Miquelon',
            'Zambia': 'Zambia',
            'Western Sahara': 'Western Sahara',
            'Estonia': 'Estonia',
            'Egypt': 'Egypt',
            'South Africa': 'South Africa',
            'Ecuador': 'Ecuador',
            'Italy': 'Italy',
            'Vietnam': 'Vietnam',
            'Solomon Islands': 'Solomon Islands',
            'Ethiopia': 'Ethiopia',
            'Somalia': 'Somalia',
            'Zimbabwe': 'Zimbabwe',
            'Saudi Arabia': 'Saudi Arabia',
            'Spain': 'Spain',
            'Eritrea': 'Eritrea',
            'Montenegro': 'Montenegro',
            'Moldova': 'Moldova',
            'Madagascar': 'Madagascar',
            'Saint Martin': 'Saint Martin',
            'Morocco': 'Morocco',
            'Monaco': 'Monaco',
            'Uzbekistan': 'Uzbekistan',
            'Myanmar': 'Myanmar',
            'Mali': 'Mali',
            'Macao': 'Macao',
            'Mongolia': 'Mongolia',
            'Marshall Islands': 'Marshall Islands',
            'Macedonia': 'Macedonia',
            'Mauritius': 'Mauritius',
            'Malta': 'Malta',
            'Malawi': 'Malawi',
            'Maldives': 'Maldives',
            'Martinique': 'Martinique',
            'Northern Mariana Islands': 'Northern Mariana Islands',
            'Montserrat': 'Montserrat',
            'Mauritania': 'Mauritania',
            'Isle of Man': 'Isle of Man',
            'Uganda': 'Uganda',
            'Tanzania': 'Tanzania',
            'Malaysia': 'Malaysia',
            'Mexico': 'Mexico',
            'Israel': 'Israel',
            'France': 'France',
            'British Indian Ocean Territory': 'British Indian Ocean Territory',
            'Saint Helena': 'Saint Helena',
            'Finland': 'Finland',
            'Fiji': 'Fiji',
            'Falkland Islands': 'Falkland Islands',
            'Micronesia': 'Micronesia',
            'Faroe Islands': 'Faroe Islands',
            'Nicaragua': 'Nicaragua',
            'The Netherlands': 'The Netherlands',
            'Norway': 'Norway',
            'Namibia': 'Namibia',
            'Vanuatu': 'Vanuatu',
            'New Caledonia': 'New Caledonia',
            'Niger': 'Niger',
            'Norfolk Island': 'Norfolk Island',
            'Nigeria': 'Nigeria',
            'New Zealand': 'New Zealand',
            'Nepal': 'Nepal',
            'Nauru': 'Nauru',
            'Niue': 'Niue',
            'Cook Islands': 'Cook Islands',
            'Kosovo': 'Kosovo',
            'Ivory Coast': 'Ivory Coast',
            'Switzerland': 'Switzerland',
            'Colombia': 'Colombia',
            'China': 'China',
            'Cameroon': 'Cameroon',
            'Chile': 'Chile',
            'Cocos Islands': 'Cocos Islands',
            'Canada': 'Canada',
            'Republic of the Congo': 'Republic of the Congo',
            'Central African Republic': 'Central African Republic',
            'Democratic Republic of the Congo': 'Democratic Republic of the Congo',
            'Czech Republic': 'Czech Republic',
            'Cyprus': 'Cyprus',
            'Christmas Island': 'Christmas Island',
            'Costa Rica': 'Costa Rica',
            'Curacao': 'Curacao',
            'Cape Verde': 'Cape Verde',
            'Cuba': 'Cuba',
            'Swaziland': 'Swaziland',
            'Syria': 'Syria',
            'Sint Maarten': 'Sint Maarten',
            'Kyrgyzstan': 'Kyrgyzstan',
            'Kenya': 'Kenya',
            'South Sudan': 'South Sudan',
            'Suriname': 'Suriname',
            'Kiribati': 'Kiribati',
            'Cambodia': 'Cambodia',
            'Saint Kitts and Nevis': 'Saint Kitts and Nevis',
            'Comoros': 'Comoros',
            'Sao Tome and Principe': 'Sao Tome and Principe',
            'Slovakia': 'Slovakia',
            'South Korea': 'South Korea',
            'Slovenia': 'Slovenia',
            'Kuwait': 'Kuwait',
            'Senegal': 'Senegal',
            'San Marino': 'San Marino',
            'Sierra Leone': 'Sierra Leone',
            'Seychelles': 'Seychelles',
            'Kazakhstan': 'Kazakhstan',
            'Cayman Islands': 'Cayman Islands',
            'Singapore': 'Singapore',
            'Sweden': 'Sweden',
            'Sudan': 'Sudan',
            'Dominican Republic': 'Dominican Republic',
            'Dominica': 'Dominica',
            'Djibouti': 'Djibouti',
            'Denmark': 'Denmark',
            'British Virgin Islands': 'British Virgin Islands',
            'Germany': 'Germany',
            'Yemen': 'Yemen',
            'Algeria': 'Algeria',
            'United States': 'United States',
            'Uruguay': 'Uruguay',
            'Mayotte': 'Mayotte',
            'United States Minor Outlying Islands': 'United States Minor Outlying Islands',
            'Lebanon': 'Lebanon',
            'Saint Lucia': 'Saint Lucia',
            'Laos': 'Laos',
            'Tuvalu': 'Tuvalu',
            'Taiwan': 'Taiwan',
            'Trinidad and Tobago': 'Trinidad and Tobago',
            'Turkey': 'Turkey',
            'Sri Lanka': 'Sri Lanka',
            'Liechtenstein': 'Liechtenstein',
            'Latvia': 'Latvia',
            'Tonga': 'Tonga',
            'Lithuania': 'Lithuania',
            'Luxembourg': 'Luxembourg',
            'Liberia': 'Liberia',
            'Lesotho': 'Lesotho',
            'Thailand': 'Thailand',
            'French Southern Territories': 'French Southern Territories',
            'Togo': 'Togo',
            'Chad': 'Chad',
            'Turks and Caicos Islands': 'Turks and Caicos Islands',
            'Libya': 'Libya',
            'Vatican': 'Vatican',
            'Saint Vincent and the Grenadines': 'Saint Vincent and the Grenadines',
            'United Arab Emirates': 'United Arab Emirates',
            'Andorra': 'Andorra',
            'Antigua and Barbuda': 'Antigua and Barbuda',
            'Afghanistan': 'Afghanistan',
            'Anguilla': 'Anguilla',
            'U.S. Virgin Islands': 'U.S. Virgin Islands',
            'Iceland': 'Iceland',
            'Iran': 'Iran',
            'Armenia': 'Armenia',
            'Albania': 'Albania',
            'Angola': 'Angola',
            'Antarctica': 'Antarctica',
            'American Samoa': 'American Samoa',
            'Argentina': 'Argentina',
            'Australia': 'Australia',
            'Austria': 'Austria',
            'Aruba': 'Aruba',
            'India': 'India',
            'Aland Islands': 'Aland Islands',
            'Azerbaijan': 'Azerbaijan',
            'Ireland': 'Ireland',
            'Indonesia': 'Indonesia',
            'Ukraine': 'Ukraine',
            'Qatar': 'Qatar',
            'Mozambique': 'Mozambique'
        };
        var $country = $('#country');

        $country.autocomplete({
            source: countries,
            treshold: 1,
            onSelectItem: callback,
        });

        $country.on('keyup', callback);
        $country.on('blur', callback);

        function callback() {
            if (!$country.val()) return;
            var list = Object.values(countries);
            if (!list.includes($country.val())) {
                $country.addClass('is-invalid');
            } else {
                $country.removeClass('is-invalid');
            }
        }
    </script>
@endpush
