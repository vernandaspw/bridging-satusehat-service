<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Sphaira\SphairaPatient;
use Illuminate\Http\Request;

class SsPasienController extends Controller
{
    public function get(Request $request)
    {
        // $pasienData = SphairaPatient::where('isActive', 1)->where('isDeleted', 0);
        $pasienData = Patient::with('SphairaPatient')->orderBy('MedicalNo', 'desc');

        if ($request->MedicalNo) {
            $pasienData->where('MedicalNo', 'like', '%' . $request->MedicalNo . '%');
        }


        $pasiens =  $pasienData->paginate(10);

        $datas = [];
        foreach ($pasiens as $pasien) {
            $datas[] = [
                'ihs' => $pasien->PatientIHS,
                "MedicalNo" => strval($pasien->MedicalNo),
                "nama_pasien" => $pasien->SphairaPatient->PatientName,
                "no_bpjs" => $pasien->SphairaPatient->BpjsCardNo,
                "nik" => $pasien->SphairaPatient->SSN,
                "no_hp" => $pasien->SphairaPatient->MobilePhoneNo1,
                "tanggal_lahir" => $pasien->SphairaPatient->DateOfBirth,
                "jenis_kelamin" => $pasien->SphairaPatient->GCSex == '0001^M' ? "L" : "P",
                'isGCSatusehat' => $pasien->isGCSatusehat,
            ];
        }
        if ($request->nik) {
            $datas = collect($datas)->where('nik', $request->nik);
        }
        if ($request->no_bpjs) {
            $datas = collect($datas)->where('no_bpjs', $request->no_bpjs);
        }
        if ($request->nama) {
            $datas = collect($datas)->filter(function ($item) use ($request) {
                return stripos($item['nama_pasien'], $request->nama) !== false;
            });
        }
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'items' => $datas,
                'current_page' => $pasiens->currentPage(),
                'first_page_url' => $pasiens->url(1),
                'from' => $pasiens->firstItem(),
                'last_page' => $pasiens->lastPage(),
                'last_page_url' => $pasiens->url($pasiens->lastPage()),
                'links' => $pasiens->links()->elements,
                'next_page_url' => $pasiens->nextPageUrl(),
                'path' => $pasiens->url($pasiens->currentPage()),
                'per_page' => $pasiens->perPage(),
                'prev_page_url' => $pasiens->previousPageUrl(),
                'to' => $pasiens->lastItem(),
                'total' => $pasiens->total(),
            ]
        ]);
    }

    public function sync()
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
    }
}
