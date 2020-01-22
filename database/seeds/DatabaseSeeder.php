<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Michel3951',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => 'michel39511@gmail.com'
        ]);
        \App\Models\Role::create([
            'name' => 'Administrator'
        ]);
        \App\Models\UserRole::create([
            'role_id' => 1,
            'user_id' => 1
        ]);

        $shippers = [
            "China Post Air Parcel", "China Post Registered AirMail", "China Post Ordinary Small Packet Plus", "HongKong Post Air Parcel", "Singapore Post", "DHL Global Mail and S.F. Express", "HongKong Post Air Mail", "Russian Air", "Special Line-YW", "Swiss Post", "Sweden Post", "UPS", "TNT", "FedEx", "ePacket", "EMS", "e-EMS",
        ];

        foreach ($shippers as $shipper) {
            \App\Models\Shipper::create([
                'name' => $shipper
            ]);
        }
    }
}
