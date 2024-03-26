<?php

namespace App\Console\Commands;

use App\Models\Patient;
use App\Models\Sphaira\SphairaPatient;
use Illuminate\Console\Command;

class PasienSphairaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sphaira:pasien';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Langkah 1: Hitung total jumlah baris yang cocok dengan kriteria
        $totalSphairaPatients = SphairaPatient::where('isActive', 1)
            ->where('isDeleted', 0)
            ->count();

        // Jumlah data yang akan diambil dalam satu iterasi
        $chunkSize = 20;

        // Hitung jumlah iterasi yang dibutuhkan berdasarkan jumlah total data dan ukuran potongan
        $totalChunks = ceil($totalSphairaPatients / $chunkSize);

        // Lakukan iterasi untuk setiap potongan data
        for ($page = 1; $page <= $totalChunks; $page++) {
        // Ambil data untuk halaman saat ini
            $sphairaPatients = SphairaPatient::where('isActive', 1)
                ->where('isDeleted', 0)
                ->orderBy('MedicalNo', 'desc')
                ->skip(($page - 1) * $chunkSize)
                ->take($chunkSize)
                ->get();

        // Langkah 2: Iterasi melalui setiap SphairaPatient pada halaman saat ini
            foreach ($sphairaPatients as $sphairaPatient) {
                // Langkah 3: Periksa apakah ada Patient dengan nomor medis yang cocok
                $patient = Patient::where('MedicalNo', $sphairaPatient->MedicalNo)->first();

                // Langkah 4: Jika tidak ada Patient yang cocok, buat entri baru dalam Patient
                if (!$patient) {
                    $newPatient = new Patient();
                    $newPatient->MedicalNo = $sphairaPatient->MedicalNo;
                    // Tambahkan atribut lainnya sesuai kebutuhan
                    $newPatient->save();
                }
            }
        }
        return true;

    }
}
