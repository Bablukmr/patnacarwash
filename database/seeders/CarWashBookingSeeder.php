<?php

namespace Database\Seeders;

use App\Models\CarWashBooking;
use Illuminate\Database\Seeder;

class CarWashBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarWashBooking::create([
            'user_id' => 1, // Assuming you have an admin user with ID 1
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'car_model' => 'Toyota Camry',
            'service_type' => 'Full Wash',
            'preferred_date' => '2025-02-01',
            'preferred_time' => '10:00:00',
            'address' => '123 Main St, City, State, 12345',
        ]);

        // Add more records as needed
    }
}
