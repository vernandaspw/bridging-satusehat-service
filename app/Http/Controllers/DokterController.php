<?php

namespace App\Http\Controllers;

use App\Models\Sphaira\SphairaParamedic;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function get(Request $req)
    {
        $data_paramedics = SphairaParamedic::where('isDeleted', 0)->where('IsActive', 1)->where('GCParamedicType', 'X0055^001')->where('ParamedicName', '!=', 'QPRO');

        if ($req->ihs_null) {
            $data_paramedics->where('ParamedicIHS', null);
        }
        if($req->ihs_sanbox_null) {
            $data_paramedics->where('ParamedicIHSsanbox', null);
        }

        $paramedics = $data_paramedics->get();
        $datas = [];
        foreach ($paramedics as $paramedic) {
            $datas[] = [
                'id' => $paramedic->ParamedicID,
                'ihs' => $paramedic->ParamedicIHS,
                'ihs_sanbox' => $paramedic->ParamedicIHSsanbox,
                'kode_dokter' => $paramedic->ParamedicCode,
                'jenis_profesi' => $paramedic->sysGeneralCode->GeneralCodeName1,
                'spesialis' => $paramedic->Specialty->SpecialtyName1,
                'nama_dokter' => $paramedic->ParamedicName,
                "jenis_kelamin" => $paramedic->GCSex == '0001^M' ? 'L' : 'P',
                "tgl_lahir" => $paramedic->DateOfBirth . " 00:00:00.000",
                "agama" => "-",
                "email" => "-",
                "nik" => $paramedic->TaxRegistrantNo,
                "alamat" => "-",
                "kota" => "-",
                "provinsi" => "-",
                "kode_pos" => "-",
                "no_hp" => "-",
                "pemeriksaan" => "0",
                "visit" => "0",
                "konsul" => "0",
                "tindakan" => "0",
                "lain" => "0",
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }

    public function getByKode($kode)
    {
        $paramedic = SphairaParamedic::where('ParamedicCode', $kode)->first();

        $data = [
            'id' => $paramedic->ParamedicID,
            'ihs' => $paramedic->ParamedicIHS,
            'ihs_sanbox' => $paramedic->ParamedicIHSsanbox,
            'kode_dokter' => $paramedic->ParamedicCode,
            'jenis_profesi' => $paramedic->sysGeneralCode->GeneralCodeName1,
            'spesialis' => $paramedic->Specialty->SpecialtyName1,
            'nama_dokter' => $paramedic->ParamedicName,
            "jenis_kelamin" => $paramedic->GCSex == '0001^M' ? 'L' : 'P',
            "tgl_lahir" => $paramedic->DateOfBirth . " 00:00:00.000",
            "agama" => "-",
            "email" => "-",
            "nik" => $paramedic->TaxRegistrantNo,
            "alamat" => "-",
            "kota" => "-",
            "provinsi" => "-",
            "kode_pos" => "-",
            "no_hp" => "-",
            "pemeriksaan" => "0",
            "visit" => "0",
            "konsul" => "0",
            "tindakan" => "0",
            "lain" => "0",
        ];

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    public function updateIHSbyKode(Request $request, $kodeDokter)
    {
        try {
            $paramedic = SphairaParamedic::where('ParamedicCode', $kodeDokter)->first();

            if ($request->isProd == true) {
                $paramedic->ParamedicIHS = $request->kodeIHS;
            } else {
                $paramedic->ParamedicIHSsanbox = $request->kodeIHS;
            }

            $paramedic->save();

            $data = [
                'id' => $paramedic->ParamedicID,
                'ihs' => $paramedic->ParamedicIHS,
                'ihs_sanbox' => $paramedic->ParamedicIHSsanbox,
                'kode_dokter' => $paramedic->ParamedicCode,
                'jenis_profesi' => $paramedic->sysGeneralCode->GeneralCodeName1,
                'spesialis' => $paramedic->Specialty->SpecialtyName1,
                'nama_dokter' => $paramedic->ParamedicName,
                "jenis_kelamin" => $paramedic->GCSex == '0001^M' ? 'L' : 'P',
                "tgl_lahir" => $paramedic->DateOfBirth . " 00:00:00.000",
                "agama" => "-",
                "email" => "-",
                "nik" => $paramedic->TaxRegistrantNo,
                "alamat" => "-",
                "kota" => "-",
                "provinsi" => "-",
                "kode_pos" => "-",
                "no_hp" => "-",
                "pemeriksaan" => "0",
                "visit" => "0",
                "konsul" => "0",
                "tindakan" => "0",
                "lain" => "0",
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
