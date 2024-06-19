<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Sphaira\SphairaRegistration;
use App\Models\Satusehat\LogEncounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationIgdController extends Controller
{
    public function get(Request $request)
    {
        $registrationData = SphairaRegistration::query();
        $registrationData->where('isDeleted', 0);
        $registrationData->where(DB::raw('SUBSTRING(RegistrationNo, 6, 2)'), '=', 'ER');
        $registrationData->where('EncounterIHS', null);
        $registrationData->whereDate('RegistrationDateTime', $request->tanggal ? $request->tanggal : date('Y-m-d'));
        $registrationData->orderBy('MedicalNo', 'desc')->orderBy('RegistrationDateTime', 'desc');
        $registrationData->where('DischargeDateTime', '!=', null);
        $registrations      = $registrationData->paginate(10);

        // return response()->json($registrations);

        // return $request->tanggal;

        $datas = [];
        foreach ($registrations->items() as $registration) {
            $statusRawat = 'RAWAT DARURAT';
            $log = LogEncounter::with('user')->where('noreg', $registration->RegistrationNo)->first();
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'location_id' => $registration->location->location_id,
                'location_name' => $registration->location->name,
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
                "ParamedicIHS" => $registration->dokter->ParamedicIHS,
                "ParamedicIHSsanbox" => $registration->dokter->ParamedicIHSsanbox,
                "nik_dokter" => $registration->dokter->TaxRegistrantNo,
                "nama_dokter" => $registration->dokter->ParamedicName,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->DischargeDateTime,
                'ss_encounter_id' => $registration->EncounterIHS,
                'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
                // 'diagnosas' => $registration
                'diagnosas' => $registration->diagnosa,
                // 'log' => $registration->getLogEncounter($registration->RegistrationNo)
                'log' => $log ? $log : null,
            ];
        }

        // $datas = collect($datas)->where('DischargeDateTime', '!=', null);
        // // $datas = collect($datas);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                // 'encounter' => $encounter,
                // 'not_encounter' => $not_encounter,
                // 'encounter_sanbox' => $encounter_sanbox,
                // 'not_encounter_sanbox' => $not_encounter_sanbox,
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
        $statusRawat = 'RAWAT DARURAT';

        foreach ($registration->diagnosa as $diag) {
            $diagnosas[] = [
                'pdiag_diagnosa' => $diag->DiagnosisCode
            ];
        }


        $datas = [
            "no_registrasi" => $registration->RegistrationNo,
            'ServiceUnitID' => $registration->ServiceUnitID,
            'RoomID' => $registration->RoomID,
            'RoomCode' => $registration->service_room->RoomCode,
            'RoomName' => $registration->service_room->RoomName,
            "nama_pasien" => $registration->pasien->PatientName,
            "ihs_pasien" => $registration->pasien->SSN,
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
            'DischargeDateTime' => $registration->DischargeDateTime,
            'diagnosas' => $diagnosas,
        ];
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
        $registrationData->where(DB::raw('SUBSTRING(RegistrationNo, 6, 2)'), '=', 'ER');

        if ($request->isProd == true) {
            $registrationData->where('EncounterIHS', null);
        } else {
            $registrationData->where('EncounterIHSsanbox', null);
        }

        $registrationData->whereDate('RegistrationDateTime', '>=', $daysAgo);
        $registrationData->whereDate('RegistrationDateTime', '<=', $tanggal);
        $registrationData->where('DischargeDateTime', '!=', null);

        $registrationData->orderBy('MedicalNo', 'desc')->orderBy('RegistrationDateTime', 'desc');

        // try {
        $registrations = $registrationData->get();

       

        $datas = [];
        foreach ($registrations as $registration) {
            $ihs_dokter ='';
            if ($request->isProd == true) {
                $ihs_dokter = $registration->dokter->ParamedicIHS; 
                $ihs_pasien = $registration->pasien->PatientIHS;
            } else {
                $ihs_dokter = $registration->dokter->ParamedicIHSsanbox; 
                $ihs_pasien = $registration->pasien->PatientIHSsanbox;
            }

            $statusRawat = 'RAWAT DARURAT';
            // dd($registration->dokter);
            $diagnosas = [];
            
            foreach ($registration->diagnosa as $diag) {
                $diagnosas[] = [
                    'pdiag_diagnosa' => $diag->DiagnosisCode
                ];
            }
           
            $datas[] = [
                "no_registrasi" => $registration->RegistrationNo,
                'ServiceUnitID' => $registration->ServiceUnitID,
                'location_ihs' => $registration->location->location_id,
                "ihs_pasien" => $ihs_pasien,
                "nama_pasien" => $registration->pasien->PatientName,
                "DateOfBirth" => $registration->pasien->DateOfBirth,
                "nik" => $registration->pasien->SSN,
                "no_mr" => $registration->MedicalNo,
                "status_rawat" => $statusRawat,
                "kode_dokter" => $registration->dokter ? $registration->dokter->ParamedicCode : null,
                "ihs_dokter" => $ihs_dokter,
                "nik_dokter" => $registration->dokter ? ($registration->dokter->TaxRegistrantNo ? $registration->dokter->TaxRegistrantNo : null) : null,
                "nama_dokter" => $registration->dokter ? ($registration->dokter->ParamedicName ? $registration->dokter->ParamedicName : null) : null,
                "nama_rekanan" => $registration->bisnisPartner->BusinessPartnerName,
                "daftar_by" => '-',
                "created_by" => "-",
                'RegistrationDateTime' => $registration->RegistrationDateTime,
                'DischargeDateTime' => $registration->DischargeDateTime,
                'ss_encounter_id' => $registration->EncounterIHS,
                'ss_encounter_id_sanbox' => $registration->EncounterIHSsanbox,
                'diagnosas' => $diagnosas,
            ];
        }

        $datas = collect($datas)->where('DischargeDateTime', '!=', null);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $datas,
        ]);
        // } catch (\Throwable $e) {
        //     dd($e);
        // }
    }
}
