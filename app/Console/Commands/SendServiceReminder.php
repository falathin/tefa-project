<?php

namespace App\Console\Commands;

require 'vendor/autoload.php';

use App\Models\Service;
use Carbon\Carbon;

class SendServiceReminder
{
    protected $signature = 'send:service-reminder';
    protected $description = 'Mengirim pengingat servis kendaraan via WhatsApp setelah 30 hari';

    public function __invoke()
    {
        // ->whereDate('service_date', '=', Carbon::now()->subDays(30)->toDateString())
        $services = Service::where('status', true)
            ->whereDate('service_date', '<=', Carbon::now()->subDays(1)->toDateString())
            ->with('vehicle.customer')
            ->get();

        if ($services->isEmpty()) {
            return; // Jika tidak ada servis yang sudah lebih dari 5 menit, keluar dari function
        }

        foreach ($services as $service) {
            $this->sendWhatsAppMessage($service);
        }
    }

    private function sendWhatsAppMessage($service)
    {
        $customer = $service->vehicle->customer;

        // Cek jika nomor kontak tidak ada atau tidak valid
        if ($customer->contact == null) {
            error_log("Nomor kontak tidak tersedia untuk pelanggan {$customer->name}.");
            return;
        } else if (!$this->validatePhoneNumber($customer->contact)) {
            error_log("Nomor kontak {$customer->contact} untuk pelanggan {$customer->name} tidak valid.");
            return;
        }

        $message = "Halo {$customer->name}, sudah 30 hari sejak servis terakhir kendaraan Anda dengan merk {$service->vehicle->brand} dan tipe {$service->vehicle->vehicle_type} . 
        Jangan lupa untuk melakukan servis rutin agar kendaraan tetap prima!";

        $token = 'TnP9LcqKBZGTA8royRMA';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $customer->contact, 'message' => $message),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        // Eksekusi cURL dan tangkap respons
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Decode respons JSON
        $responseData = json_decode($response, true);

        // Cek jika pesan gagal dikirim
        if ($httpCode != 200 || !isset($responseData['status']) || $responseData['status'] != 'success') {
            $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Unknown error';
            error_log("Gagal mengirim pesan WhatsApp ke {$customer->contact} (Pelanggan: {$customer->name}): {$errorMessage}");
        } else {
            // Jika berhasil, log informasi pengiriman pesan
            error_log("Pesan WhatsApp berhasil dikirim ke {$customer->contact} (Pelanggan: {$customer->name}).");
        }
    }


    function validatePhoneNumber($phone)
    {
        // Regex untuk nomor handphone diawali 08
        $regex = '/^08\d{8,12}$/';
        return preg_match($regex, $phone);
    }
}
