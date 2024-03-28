<?php

namespace App\Http\Controllers;

use App\Models\Sphaira\SphairaParamedic;
use App\Models\Sphaira\SphairaRegistration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function get(Request $request)
    {

        $registrationData = SphairaRegistration::where('isDeleted', 0);

        if ($request->kode_dokter) {
            $registrationData->where('ParamedicID', $request->kode_dokter);
        }
        if ($request->tanggal) {
            $registrationData->where('RegistrationDateTime', $request->tanggal);
        }
        if ($request->status_rawat) {
            $registrationData->where('RegistrationNo', 'LIKE', $request->status_rawat);
        }

        if ($request->take) {
            $registrationData->take($request->take);
        } else {
            $registrationData->take(200);
        }

        $registrationData->where('RegistrationNo', 'LIKE', '%ER%')
            ->orWhere('RegistrationNo', 'LIKE', '%RJ%')
            ->orWhere('RegistrationNo', 'LIKE', '%RI%')
        ;

        $registrations = $registrationData->orderBy('MedicalNo', 'desc')
            ->orderBy('RegistrationDateTime', 'desc')->get();

        $datas = [];
        foreach ($registrations as $registration) {
            if (strpos($registration->RegistrationNo, 'RI') !== false) {
                $statusRawat = 'RAWAT INAP';
            } elseif (strpos($registration->RegistrationNo, 'RJ') !== false) {
                $statusRawat = 'RAWAT JALAN';
            } elseif (strpos($registration->RegistrationNo, 'ER') !== false) {
                $statusRawat = 'ER';
            } else {
                $statusRawat = 'lainnya';
            }
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                "nama_pasien" => $registration->pasien->PatientName,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter->ParamedicCode,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'ss_encounter_id' => $registration->EncounterIHS,
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->DischargeDateTime,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }

    public function getByNoreg(Request $request)
    {
        $noreg = $request->noreg;
        $registration = SphairaRegistration::where('RegistrationNo', $noreg)->first();
        if (strpos($registration->RegistrationNo, 'RI') !== false) {
            $statusRawat = 'RAWAT INAP';
        } elseif (strpos($registration->RegistrationNo, 'RJ') !== false) {
            $statusRawat = 'RAWAT JALAN';
        } elseif (strpos($registration->RegistrationNo, 'ER') !== false) {
            $statusRawat = 'ER';
        } else {
            $statusRawat = 'lainnya';
        }
        $datas = [
            "no_registrasi" => $registration->RegistrationNo,
            'ServiceUnitID' => $registration->ServiceUnitID,
            "nama_pasien" => $registration->pasien->PatientName,
            "nik" => $registration->pasien->SSN,
            "no_mr" => $registration->MedicalNo,
            "status_rawat" => $statusRawat,
            "kode_dokter" => $registration->dokter->ParamedicCode,
            "nama_dokter" => $registration->dokter->ParamedicName,
            "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
            "daftar_by" => '-',
            "created_by" => "-",
            'ss_encounter_id' => $registration->EncounterIHS,
            'RegistrationDateTime' => $registration->RegistrationDateTime,
            'DischargeDateTime' => $registration->DischargeDateTime,
        ];
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }

    public function getByDokter()
    {
        $paramedics = SphairaParamedic::where('isDeleted', 0)->where('GCParamedicType', 'X0055^001')->where('ParamedicName', '!=', 'QPRO')->get();
        $datas = [];
        foreach ($paramedics as $paramedic) {
            $datas[] = [
                'id' => $paramedic->ParamedicID,
                'kode_dokter' => $paramedic->ParamedicCode,
                'jenis_profesi' => $paramedic->sysGeneralCode->GeneralCodeName1,
                'spesialis' => $paramedic->Specialty->SpecialtyName1,
                'nama_dokter' => $paramedic->ParamedicName,
                "jenis_kelamin" => $paramedic->GCSex == '0001^M' ? 'L' : 'P',
                "tgl_lahir" => $paramedic->DateOfBirth . " 00:00:00.000",
                "agama" => "-",
                "email" => "-",
                "nik" => $paramedic->SSN,
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
        };
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }


    public function updateEncounterId(Request $request)
    {
        $noreg = $request->noreg;
        $encounter_id = $request->encounter_id;

        try {
            $registration = SphairaRegistration::where('RegistrationNo', $noreg)->first();

            if($request->isProd == true) {
                $registration->EncounterIHS = $encounter_id;
            }else{
                $registration->EncounterIHSsanbox = $encounter_id;
            }

            $registration->save();

            if (strpos($registration->RegistrationNo, 'RI') !== false) {
                $statusRawat = 'RAWAT INAP';
            } elseif (strpos($registration->RegistrationNo, 'RJ') !== false) {
                $statusRawat = 'RAWAT JALAN';
            } elseif (strpos($registration->RegistrationNo, 'ER') !== false) {
                $statusRawat = 'ER';
            } else {
                $statusRawat = 'lainnya';
            }
            $datas = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                "nama_pasien" => $registration->pasien->PatientName,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter->ParamedicCode,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'ss_encounter_id' => $registration->EncounterIHS,
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->DischargeDateTime,
            ];
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $datas,
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
