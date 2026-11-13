<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAspirationRequest;
use App\Models\Aspiration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAspirationController extends Controller
{
    public function index()
    {
        $aspirations = Aspiration::with('user')->latest()->get();

        $analytics = [
            'total' => Aspiration::count(),
            'menunggu' => Aspiration::where('status', 'Menunggu')->count(),
            'fasilitas' => Aspiration::where('category', 'Fasilitas')->count(),
            'akademik' => Aspiration::where('category', 'Akademik')->count(),
            'layanan' => Aspiration::where('category', 'Layanan')->count(),
        ];

        return view('admin.dashboard', compact('aspirations', 'analytics'));
    }

    public function updateStatus(UpdateAspirationRequest $request, int $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        $aspiration->update([
            'status' => $request->validated('status'),
            'response' => $request->validated('response'),
        ]);

        return redirect()->back()->with('success', 'Status & tanggapan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Aspiration::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }

    public function exportMonthlyPdf()
    {
        $aspirations = Aspiration::with('user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $pdf = Pdf::loadView('pdf.monthly_report', compact('aspirations'));

        return $pdf->download('Rekap_Aspirasi_'.now()->format('Y_m').'.pdf');
    }

    public function exportMonthlyCsv(): StreamedResponse
    {
        $fileName = 'Rekap_Aspirasi_'.now()->format('Y_m').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel membaca UTF-8 dengan benar
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'NIM', 'Nama', 'Kategori', 'Judul', 'Deskripsi', 'Status', 'Tanggapan', 'Tanggal']);

            Aspiration::with('user')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->chunkById(100, function ($aspirations) use ($handle) {
                    foreach ($aspirations as $row) {
                        fputcsv($handle, [
                            $row->id,
                            $row->user->nim,
                            $row->user->name,
                            $row->category,
                            $row->title,
                            $row->description,
                            $row->status,
                            $row->response ?? '-',
                            $row->created_at->format('d M Y H:i'),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
