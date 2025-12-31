<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Certificate::create(['user_id' => 1, 'original_certificate' => 'certificate.pdf', 'certificate' => 'certificate.pdf', 'status' => 'pending']);
        Certificate::create(['user_id' => 1, 'original_certificate' => 'certificate2.pdf', 'certificate' => 'certificate2.pdf', 'status' => 'pending']);
        Certificate::create(['user_id' => 2, 'original_certificate' => 'certificate3.pdf', 'certificate' => 'certificate3.pdf', 'status' => 'pending']);
        Certificate::create(['user_id' => 3, 'original_certificate' => 'certificate4.pdf', 'certificate' => 'certificate4.pdf', 'status' => 'approved', 'description' => 'Proses verifikasi sertifikat selesai, sertifikat diterima.']);
        Certificate::create(['user_id' => 4, 'original_certificate' => 'certificate5.pdf', 'certificate' => 'certificate5.pdf', 'status' => 'pending']);
        Certificate::create(['user_id' => 4, 'original_certificate' => 'certificate6.pdf', 'certificate' => 'certificate6.pdf', 'status' => 'pending']);
    }
}
