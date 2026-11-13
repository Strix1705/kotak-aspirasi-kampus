<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAspirationRequest;
use App\Models\Aspiration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaAspirationController extends Controller
{
    /**
     * Daftar aspirasi milik user login saja.
     * where('user_id', Auth::id()) mencegah IDOR.
     */
    public function index()
    {
        $aspirations = Aspiration::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.dashboard', compact('aspirations'));
    }

    public function store(StoreAspirationRequest $request)
    {
        Aspiration::create([
            'user_id' => Auth::id(),
            'title' => $request->validated('title'),
            'category' => $request->validated('category'),
            'description' => $request->validated('description'),
            'status' => 'Menunggu',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Aspirasi berhasil dikirim.');
    }

    /**
     * Hapus/batalkan laporan sendiri yang masih berstatus Menunggu.
     * Scope user_id + status = proteksi IDOR + aturan bisnis.
     */
    public function destroy(int $id)
    {
        $aspiration = Aspiration::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!$aspiration->isMenunggu()) {
            return redirect()->back()->with('error', 'Laporan yang sedang/diproses tidak dapat dibatalkan.');
        }

        $aspiration->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dibatalkan.');
    }

    public function exportPdf(int $id)
    {
        $aspiration = Aspiration::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.ticket', compact('aspiration'));

        return $pdf->download("Tiket_Aspirasi_{$aspiration->id}.pdf");
    }
}
