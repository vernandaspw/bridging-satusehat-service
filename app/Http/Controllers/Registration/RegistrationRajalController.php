<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Sphaira\SphairaParamedic;
use App\Models\Sphaira\SphairaRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrationRajalController extends Controller
{
    // public function get(Request $request)
    // {
    //     // $db = 'rme';
    //     $registrationData = RmeRegistration::query();
    //     $registrationData->where('reg_deleted', 0);
    //     $registrationData->where('reg_no', 'LIKE', '%RJ%');
    //     if ($request->tanggal) {
    //         $registrationData->whereDate('reg_tgl', $request->tanggal);
    //     } else {
    //         $registrationData->whereDate('reg_tgl', date('Y-m-d'));
    //     }
    //     $registrationData->orderBy('reg_medrec', 'desc')->orderBy('reg_tgl', 'desc');
    //     $registrations = $registrationData->paginate(10);

    //     $datas = [];
    //     foreach ($registrations->items() as $registration) {
    //         if (strpos($registration->reg_no, 'RJ') !== false) {
    //             $statusRawat = 'RAWAT JALAN';
    //         } else {
    //             $statusRawat = '-';
    //         }
    //         $datas[] = [
    //             "no_registrasi" => $registration->reg_no,
    //             'RoomCode' => $registration->reg_poli,
    //             'RoomName' => $registration->service_room->RoomName,
    //             "nama_pasien" => $registration->pasien->PatientName,
    //             "nik" => $registration->pasien->SSN,
    //             "DateOfBirth" => $registration->pasien->DateOfBirth,
    //             "no_mr" => $registration->MedicalNo,
    //             "status_rawat" => $statusRawat,
    //             "kode_dokter" => $registration->reg_dokter,
    //             "nama_dokter" => $registration->dokter->ParamedicName,
    //             "nama_rekanan" => $registration->bisnis_partner->BusinessPartnerName,
    //             "daftar_by" => '-',
    //             "created_by" => "-",
    //             'ss_encounter_id' => '-',
    //         ];
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'success',
    //         'data' => [
    //             'items' => $datas,
    //             'current_page' => $registrations->currentPage(),
    //             'first_page_url' => $registrations->url(1),
    //             'from' => $registrations->firstItem(),
    //             'last_page' => $registrations->lastPage(),
    //             'last_page_url' => $registrations->url($registrations->lastPage()),
    //             'links' => $registrations->links()->elements,
    //             'next_page_url' => $registrations->nextPageUrl(),
    //             'path' => $registrations->url($registrations->currentPage()),
    //             'per_page' => $registrations->perPage(),
    //             'prev_page_url' => $registrations->previousPageUrl(),
    //             'to' => $registrations->lastItem(),
    //             'total' => $registrations->total(),
    //         ],
    //     ]);
    // }

    public function getDate(Request $request)
    {
        $registrationData = SphairaRegistration::query();
        $registrationData->where('isDeleted', 0);
        $registrationData->where('RegistrationNo', 'LIKE', '%RJ%');
        $registrationData->where('EncounterIHS', null);
        $registrationData->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));

        $registrationData->orderBy('MedicalNo', 'desc')->orderBy('RegistrationDateTime', 'desc');

        $registrations = $registrationData->get();

        $datas = [];
        foreach ($registrations as $registration) {
            if (strpos($registration->RegistrationNo, 'RJ') !== false) {
                $statusRawat = 'RAWAT JALAN';
            } else {
                $statusRawat = '-';
            }
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                'RoomID' => $registration->RoomID,
                'RoomCode' => $registration->service_room->RoomCode,
                'RoomName' => $registration->service_room->RoomName,
                "nama_pasien" => $registration->pasien->PatientName,
                "DateOfBirth" => $registration->pasien->DateOfBirth,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter->ParamedicCode,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->getRmeDischargeDateTime($registration->RegistrationNo),
                'ss_encounter_id' => $registration->EncounterIHS,
                'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
                'diagnosas' => $registration->getRmeDiagnosa($registration->RegistrationNo),
            ];
        }

        $datas = collect($datas)->where('DischargeDateTime', '!=', null);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
    }

    public function getlastday(Request $request)
    {
        $jml_hari = $request->hari ? $request->hari : 0;
        $tanggal = $request->tanggal ? $request->tanggal : Carbon::today()->toDateString();
        $daysAgo = Carbon::parse($tanggal)->subDays($jml_hari)->startOfDay()->toDateString();

        $registrationData = SphairaRegistration::query();
        $registrationData->where('isDeleted', 0);
        $registrationData->where('RegistrationNo', 'LIKE', '%RJ%');

        if ($request->isProd == true) {
            $registrationData->where('EncounterIHS', null);
        } else {
            $registrationData->where('EncounterIHSsanbox', null);
        }

        $registrationData->whereDate('RegistrationDateTime', '>=', $daysAgo);
        $registrationData->whereDate('RegistrationDateTime', '<=', $tanggal);

        $registrationData->orderBy('MedicalNo', 'desc')->orderBy('RegistrationDateTime', 'desc');

        try {
            $registrations = $registrationData->get();

        $datas = [];
        foreach ($registrations as $registration) {
            if (strpos($registration->RegistrationNo, 'RJ') !== false) {
                $statusRawat = 'RAWAT JALAN';
            } else {
                $statusRawat = '-';
            }
            // dd($registration->dokter);
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                'RoomID' => $registration->RoomID,
                'RoomCode' => $registration->service_room->RoomCode,
                'RoomName' => $registration->service_room->RoomName,
                "nama_pasien" => $registration->pasien->PatientName,
                "DateOfBirth" => $registration->pasien->DateOfBirth,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter->ParamedicCode,
                "nik_dokter" => $registration->dokter->TaxRegistrantNo,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->getRmeDischargeDateTime($registration->RegistrationNo),
                'ss_encounter_id' => $registration->EncounterIHS,
                'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
                'diagnosas' => $registration->getRmeDiagnosa($registration->RegistrationNo),
            ];
        }

        $datas = collect($datas)->where('DischargeDateTime', '!=', null);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function get(Request $request)
    {

        $registrationData = SphairaRegistration::query();
        $registrationData->where('isDeleted', 0);
        $registrationData->where('RegistrationNo', 'LIKE', '%RJ%');
        $registrationData->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));

        $registrationData->orderBy('MedicalNo', 'desc')->orderBy('RegistrationDateTime', 'desc');

        $registrations = $registrationData->paginate(10);
        // return response()->json($registrations);
        // cek JUMLAH ENCOUNTER
        $cekEncounter = SphairaRegistration::query();
        $cekEncounter->where('isDeleted', 0);
        $cekEncounter->where('RegistrationNo', 'LIKE', '%RJ%');
        $cekEncounter->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));
        $encounter = $cekEncounter->whereNotNull('EncounterIHS')->count();

        $cekEncounterSanbox = SphairaRegistration::query();
        $cekEncounterSanbox->where('isDeleted', 0);
        $cekEncounterSanbox->where('RegistrationNo', 'LIKE', '%RJ%');
        $cekEncounterSanbox->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));
        $encounter_sanbox = $cekEncounterSanbox->whereNotNull('EncounterIHSsanbox')->count();

        $cekNotEncounter = SphairaRegistration::query();
        $cekNotEncounter->where('isDeleted', 0);
        $cekNotEncounter->where('RegistrationNo', 'LIKE', '%RJ%');
        $cekNotEncounter->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));
        $not_encounter = $cekNotEncounter->where('EncounterIHS', null)->count();

        $cekNotEncounterSanbox = SphairaRegistration::query();
        $cekNotEncounterSanbox->where('isDeleted', 0);
        $cekNotEncounterSanbox->where('RegistrationNo', 'LIKE', '%RJ%');
        $cekNotEncounterSanbox->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));
        $not_encounter_sanbox = $cekNotEncounterSanbox->where('EncounterIHSsanbox', null)->count();

        $datas = [];
        foreach ($registrations->items() as $registration) {
            if (strpos($registration->RegistrationNo, 'RJ') !== false) {
                $statusRawat = 'RAWAT JALAN';
            } else {
                $statusRawat = '-';
            }
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                'RoomID' => $registration->RoomID,
                'RoomCode' => $registration->service_room->RoomCode,
                'RoomName' => $registration->service_room->RoomName,
                "nama_pasien" => $registration->pasien->PatientName,
                "DateOfBirth" => $registration->pasien->DateOfBirth,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter->ParamedicCode,
                "nik_dokter" => $registration->dokter->TaxRegistrantNo,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->getRmeDischargeDateTime($registration->RegistrationNo),
                'ss_encounter_id' => $registration->EncounterIHS,
                'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
                // 'diagnosas' => $registration
                'diagnosas' => $registration->getRmeDiagnosa($registration->RegistrationNo),
            ];
        }
        $datas = collect($datas)->where('DischargeDateTime', '!=', null);
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'encounter' => $encounter,
                'not_encounter' => $not_encounter,
                'encounter_sanbox' => $encounter_sanbox,
                'not_encounter_sanbox' => $not_encounter_sanbox,
                'items' => $datas,
                'current_page' => $registrations->currentPage(),
                'first_page_url' => $registrations->url(1),
                'from' => $registrations->firstItem(),
                'last_page' => $registrations->lastPage(),
                'last_page_url' => $registrations->url($registrations->lastPage()),
                'links' => $registrations->links()->elements,
                'next_page_url' => $registrations->nextPageUrl(),
                'path' => $registrations->url($registrations->currentPage()),
                'per_page' => $registrations->perPage(),
                'prev_page_url' => $registrations->previousPageUrl(),
                'to' => $registrations->lastItem(),
                'total' => $registrations->total(),
            ],
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
            'RoomID' => $registration->RoomID,
            'RoomCode' => $registration->service_room->RoomCode,
            'RoomName' => $registration->service_room->RoomName,
            "nama_pasien" => $registration->pasien->PatientName,
            "nik" => $registration->pasien->SSN,
            "no_mr" => $registration->MedicalNo,
            "status_rawat" => $statusRawat,
            "kode_dokter" => $registration->dokter->ParamedicCode,
            "nik_dokter" => $registration->dokter->TaxRegistrantNo,
            "nama_dokter" => $registration->dokter->ParamedicName,
            "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
            "daftar_by" => '-',
            "created_by" => "-",
            'ss_encounter_id' => $registration->EncounterIHS,
            'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
            'RegistrationDateTime' => $registration->RegistrationDateTime,
            'DischargeDateTime' => $registration->getRmeDischargeDateTime($registration->RegistrationNo),
            'diagnosas' => $registration->rmeDiagnosa,
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

}
