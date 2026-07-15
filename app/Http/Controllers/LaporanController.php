<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input awal dari dashboard
        $request->validate([
            'departemen' => 'required',
            'data' => 'required|array'
        ]);

        // 2. Ambil URL Apps Script dari file .env
        $appsScriptUrl = env('APPS_SCRIPT_URL');

        if (!$appsScriptUrl) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Konfigurasi APPS_SCRIPT_URL di file .env belum diisi!'
            ], 500);
        }

        // 3. Susun data JSON yang akan ditembak ke Google Apps Script
        $payload = [
            'pendeta' => $request->input('pendeta') ?? '-',
            'distrik' => $request->input('distrik') ?? '-',
            'triwulan' => $request->input('triwulan') ?? '-',
            'tahun' => $request->input('tahun') ?? '-',
            'departemen' => $request->input('departemen'),
            'data' => $request->input('data')
        ];

        try {
            // Ditambahkan options untuk mengabaikan verifikasi SSL lokal & mengikuti redirect Google
            $response = Http::withoutVerifying()
                ->withOptions([
                    'allow_redirects' => true
                ])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($appsScriptUrl, $payload);

            // Cek apakah respon berhasil (200) atau ada respon sukses dari Apps Script
            if ($response->successful() || $response->status() == 302) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Laporan berhasil terkirim dan disimpan langsung ke Google Sheets!'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim ke Google Sheets. Kode Status: ' . $response->status()
                ], 500);
            }

        } catch (\Exception $e) { // Di sini tanda penutup } untuk blok try sudah diperbaiki
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat menghubungi Google Sheets: ' . $e->getMessage()
            ], 500);
        }
    } // Penutup fungsi store
} // Penutup class LaporanController