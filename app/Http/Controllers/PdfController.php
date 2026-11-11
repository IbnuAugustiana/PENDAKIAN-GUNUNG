<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pendaftaran;

class PdfController extends Controller
{
    /**
     * Generate PDF for approved pendaftaran
     */
    public function downloadPendaftaran($id)
    {
        $pendaftaran = Pendaftaran::with(['user', 'gunung', 'rutePendakian', 'anggotaPendakian'])
            ->where('user_id', auth()->id())
            ->where('status', 'disetujui')
            ->findOrFail($id);

        $data = [
            'pendaftaran' => $pendaftaran,
            'title' => 'Bukti Pendaftaran SIMAKSI'
        ];

        $pdf = Pdf::loadView('pdf.pendaftaran', $data);

        return $pdf->download('pendaftaran-simaksi-' . $pendaftaran->id_pendaftaran . '.pdf');
    }
}
