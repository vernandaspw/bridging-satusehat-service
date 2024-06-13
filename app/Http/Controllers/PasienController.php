<?php

namespace App\Http\Controllers;

use App\Models\Sphaira\SphairaPatient;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function get(Request $request)
    {

        $pasienData = SphairaPatient::where('isActive', 1)->where('isDeleted', 0);

        if ($request->no_mr) {
            $pasienData->where('MedicalNo', $request->no_mr);
        }
        if ($request->no_bpjs) {
            $pasienData->where('BpjsCardNo', $request->no_bpjs);
        }
        if ($request->nik) {
            $pasienData->where('SSN', $request->nik);
        }
        if ($request->nama) {
            $pasienData->where('PatienName', $request->nama);
        }
        if ($request->take) {
            $pasienData->take($request->take);
        } else {
            $pasienData->take(1000);
        }

        $pasiens = $pasienData->orderBy('MedicalNo', 'desc')->get();
        $datas = [];
        foreach ($pasiens as $pasien) {
            $datas[] = [
                "id" => "-",
                'ihs' => $pasien->PatientIHS,
                'ihs_sanbox' => $pasien->PatientIHSsanbox,
                "no_mr" => strval($pasien->MedicalNo),
                "nama_pasien" => $pasien->PatientName,
                "no_bpjs" => $pasien->BpjsCardNo,
                "nik" => $pasien->SSN,
                "no_hp" => $pasien->MobilePhoneNo1,
                "tanggal_lahir" => $pasien->DateOfBirth,
                "jenis_kelamin" => $pasien->GCSex == '0001^M' ? "L" : "P",
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }

    public function getByNik($nik)
    {

        $pasien = SphairaPatient::where('SSN', $nik)->first();

        $datas = [
            "id" => "-",
            'ihs' => $pasien->PatientIHS,
            'ihs_sanbox' => $pasien->PatientIHSsanbox,
            "no_mr" => strval($pasien->MedicalNo),
            "nama_pasien" => $pasien->PatientName,
            "no_bpjs" => $pasien->BpjsCardNo,
            "nik" => $pasien->SSN,
            "no_hp" => $pasien->MobilePhoneNo1,
            "tanggal_lahir" => $pasien->DateOfBirth,
            "jenis_kelamin" => $pasien->GCSex == '0001^M' ? "L" : "P",
        ];
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }
    public function updateIHSByNorm(Request $request, $norm)
    {
        try {
            $pasien = SphairaPatient::where('MedicalNo',$norm)->first();
            // dd($request->isProd);
            // return response()->json($request);
            
            if($request->isProd == 'true' || $request->isProd == '1' || $request->isProd == true) {
                $pasien->PatientIHS = $request->kodeIHS;
            }else{
                $pasien->PatientIHSsanbox = $request->kodeIHS;
            }

            $pasien->save();

            $data = [
                "id" => "-",
                'ihs' => $pasien->PatientIHS,
                'ihs_sanbox' => $pasien->PatientIHSsanbox,
                "no_mr" => strval($pasien->MedicalNo),
                "nama_pasien" => $pasien->PatientName,
                "no_bpjs" => $pasien->BpjsCardNo,
                "nik" => $pasien->SSN,
                "no_hp" => $pasien->MobilePhoneNo1,
                "tanggal_lahir" => $pasien->DateOfBirth,
                "jenis_kelamin" => $pasien->GCSex == '0001^M' ? "L" : "P",
            ];

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $e->getMessage(),
            ]);
        }
    }
}
