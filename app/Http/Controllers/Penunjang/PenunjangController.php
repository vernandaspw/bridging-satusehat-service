<?php

namespace App\Http\Controllers\Penunjang;

use App\Http\Controllers\Controller;
use App\Models\Sphaira\SphairaPenunjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenunjangController extends Controller
{
    // GCItem Type :
    // X0001^02 : Farmasi
    // X0001^04 : Laboratorium
    // X0001^05 : Imaging

    public function farmasi()
    {
        $PenunjangData = SphairaPenunjang::query();
        $PenunjangData->where('isDeleted', 0);
        $PenunjangData->where('IsActive', 1);
        $PenunjangData->where('GCItemType', 'X0001^02');
        $Penunjangs   = $PenunjangData->get();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'items' => $Penunjangs
            ],
        ]);
    }

    public function laboratorium()
    {
        $PenunjangData = SphairaPenunjang::query();
        $PenunjangData->where('isDeleted', 0);
        $PenunjangData->where('IsActive', 1);
        $PenunjangData->where('GCItemType', 'X0001^04');
        $Penunjangs   = $PenunjangData->get();
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'items' => $Penunjangs
            ],
        ]);
    }

    public function radiologi()
    {
        $PenunjangData = SphairaPenunjang::query();
        $PenunjangData->where('isDeleted', 0);
        $PenunjangData->where('IsActive', 0);
        $PenunjangData->where('GCItemType', 'X0001^05');
        $Penunjangs   = $PenunjangData->get();
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'items' => $Penunjangs
            ],
        ]);
    }

}