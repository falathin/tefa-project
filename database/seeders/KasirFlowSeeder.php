<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KasirFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer
        Customer::create([
            'name' => 'Customer',
            'contact' => '666',
            'address' => 'bekasi',
        ]);
        // Schema::create('customers', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('contact')->nullable();
        //     $table->text('address')->nullable();
        //     $table->timestamps();
        // });

        
        // Kendaraan
        $customer = Customer::first(); // Anda bisa menggunakan ID lain jika perlu
        Vehicle::create([
            'license_plate' => 'T 1 SU',
            'engine_code' => 'RB26',
            'color' => 'BlueDongker',
            'production_year' => 2016,
            'customer_id' =>  $customer['id']
        ]);
        // $table->string('license_plate');
        //     $table->string('vehicle_type')->nullable(); // Changed column name to 'vehicle_type'
        //     $table->string('engine_code')->nullable();
        //     $table->string('color')->nullable();
        //     $table->integer('production_year')->nullable();
        //     $table->string('image')->nullable();
        //     $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        
        // Service
        $vehicle = Vehicle::first(); 
        Service::create([
            'vehicle_id' => $vehicle['id'],
            'complain' => 'waduh banget inputnya',
            'current_mileage' => 100,
            'service_date' => Carbon::now()->toDateString(),
        ]);
        
        // Schema::create('services', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
        //     $table->text('complaint'); // Keluhan servis
        //     $table->integer('current_mileage'); // Kilometer saat servis
        //     $table->decimal('service_fee', 10, 2); // Biaya servis
        //     $table->date('service_date'); // Tanggal servis
        //     $table->decimal('total_cost', 10, 2); // Total biaya
        //     $table->decimal('payment_received', 10, 2); // Pembayaran diterima
        //     $table->decimal('change', 10, 2); // Kembalian pembayaran
        //     $table->enum('service_type', ['light', 'medium', 'heavy'])->default('light'); // Jenis servis
        //     $table->string('status')->default('in progress'); // Status servis: 'in progress', 'completed', 'pending'
        //     $table->text('additional_notes')->nullable(); // Deskripsi tambahan terkait servis
        //     $table->string('technician_name')->nullable(); // Nama teknisi yang menangani
        //     $table->string('payment_proof')->nullable(); // Path bukti pembayaran
        //     $table->timestamps();
        // });
    }
}
