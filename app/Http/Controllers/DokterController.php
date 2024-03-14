<?php

namespace App\Http\Controllers;

class DokterController extends Controller
{
    public function get()
    {
        $data = [
            [
                "kode_dokter"=> "111",
                "jenis_profesi"=> "DOKTER SPESIALIS",
                "spesialis"=> "SPESIALIS PENYAKIT DALAM",
                "nama_dokter"=> "dr. Agung B Prasetiyono, Sp.PD",
                "jenis_kelamin"=> "L",
                "tgl_lahir"=> "1972-06-08 00:00:00.000",
                "agama"=> "ISLAM",
                "email"=> "agungbprasetiyo91@gmail.com",
                "nik"=> "1872010806720001",
                "alamat"=> "Jl. Kamboja No. 19 RT/RW. 040/007 Metro Pusat",
                "kota"=> "Metro",
                "provinsi"=> "Lampung",
                "kode_pos"=> "34111",
                "no_hp"=> "082328010445",
                "pemeriksaan"=> "10.0000",
                "visit"=> "10.0000",
                "konsul"=> "25.0000",
                "tindakan"=> "30.0000",
                "lain"=> "30.0000"
            ]
        ];
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }
}
