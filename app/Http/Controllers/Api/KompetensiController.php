<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KompetensiKeahlian;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index()
    {
        $kompetensi = KompetensiKeahlian::all();

        return response()->json($kompetensi->map(function ($item) {
            return [
                'kompetensi_id' => $item->kompetensi_id,
                'kompetensi_name' => $item->kompetensi_name,
                'program_id' => $item->program_id,
                'program_name' => $item->program_name,
                'bidang_id' => $item->bidang_id,
                'bidang_name' => $item->bidang_name,
            ];
        }));
    }
}
