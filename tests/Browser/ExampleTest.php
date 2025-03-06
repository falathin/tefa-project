use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MultipleCustomerDataTest extends DuskTestCase
{
    /**
     * Test untuk memasukkan data pelanggan pertama.
     *
     * @return void
     */
    public function testInputDataPelangganPertama()
    {
        $this->browse(function (Browse r $browser) {
            $browser->visit('http://127.0.0.1:8000/customer/create') // URL form yang sesuai
                    ->waitFor('@form') // Tunggu sampai form tersedia
                    ->type('@customer_name-field', 'John Doe') // Isi nama pelanggan
                    ->type('@phone_number-field', '081234567890') // Isi nomor HP
                    ->type('@address-field', '123 Elm Street, Springfield') // Isi alamat
                    ->type('@vehicle_make-field', 'Daihatsu') // Isi merk kendaraan
                    ->type('@vehicle_type-field', 'Xenia 1.5 A/T') // Isi tipe kendaraan
                    ->type('@vehicle_plate-field', 'B1234XYZ') // Isi nomor plat kendaraan
                    ->type('@vehicle_color-field', 'Red') // Isi warna kendaraan
                    ->type('@vehicle_year-field', '2020') // Isi tahun produksi kendaraan
                    ->type('@vehicle_engine_code-field', '1NZ-FE') // Isi kode mesin kendaraan
                    
                    ->waitForText('Customer data saved') // Tunggu konfirmasi pengisian berhasil
                    ->pause(500); // Waktu tunggu setelah pengisian form
        });
    }

    /**
     * Test untuk memasukkan data pelanggan kedua.
     *
     * @return void
     */
    public function testInputDataPelangganKedua()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/customer/create') // URL form yang sesuai
                    ->waitFor('@form') // Tunggu sampai form tersedia
                    ->type('@customer_name-field', 'Jane Smith') // Isi nama pelanggan
                    ->type('@phone_number-field', '082345678901') // Isi nomor HP
                    ->type('@address-field', '456 Oak Avenue, Rivertown') // Isi alamat
                    ->type('@vehicle_make-field', 'Honda') // Isi merk kendaraan
                    ->type('@vehicle_type-field', 'Civic 1.8 A/T') // Isi tipe kendaraan
                    ->type('@vehicle_plate-field', 'B2345ABC') // Isi nomor plat kendaraan
                    ->type('@vehicle_color-field', 'Blue') // Isi warna kendaraan
                    ->type('@vehicle_year-field', '2019') // Isi tahun produksi kendaraan
                    ->type('@vehicle_engine_code-field', 'R18A') // Isi kode mesin kendaraan
                    
                    ->waitForText('Customer data saved') // Tunggu konfirmasi pengisian berhasil
                    ->pause(500); // Waktu tunggu setelah pengisian form
        });
    }

    /**
     * Test untuk memasukkan data pelanggan ketiga.
     *
     * @return void
     */
    public function testInputDataPelangganKetiga()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/customer/create') // URL form yang sesuai
                    ->waitFor('@form') // Tunggu sampai form tersedia
                    ->type('@customer_name-field', 'Samuel Green') // Isi nama pelanggan
                    ->type('@phone_number-field', '083456789012') // Isi nomor HP
                    ->type('@address-field', '789 Pine Road, Lakeside') // Isi alamat
                    ->type('@vehicle_make-field', 'Toyota') // Isi merk kendaraan
                    ->type('@vehicle_type-field', 'Avanza 1.3 M/T') // Isi tipe kendaraan
                    ->type('@vehicle_plate-field', 'B3456DEF') // Isi nomor plat kendaraan
                    ->type('@vehicle_color-field', 'Silver') // Isi warna kendaraan
                    ->type('@vehicle_year-field', '2018') // Isi tahun produksi kendaraan
                    ->type('@vehicle_engine_code-field', 'K3-VE') // Isi kode mesin kendaraan
                    
                    ->waitForText('Customer data saved') // Tunggu konfirmasi pengisian berhasil
                    ->pause(500); // Waktu tunggu setelah pengisian form

                    ->press('@submit-button') // Tekan tombol submit
        });
    }
}
