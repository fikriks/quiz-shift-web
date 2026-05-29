<?php

namespace App\Controllers;

use App\Models\KuisModel;
use App\Models\DetailKuisModel;
use App\Models\PesertaModel;

class HasilController extends BaseController
{
    protected $kuisModel;
    protected $detailKuisModel;
    protected $pesertaModel;

    public function __construct()
    {
        $this->kuisModel = new KuisModel();
        $this->detailKuisModel = new DetailKuisModel();
        $this->pesertaModel = new PesertaModel();
    }

    public function index()
    {
        $this->requireAuth();

        $this->data['page_title'] = 'Hasil Kuis';

        if ($this->currentUser['hak_akses'] === 'ADMIN') {
            $this->data['hasil'] = $this->kuisModel->getCompletedKuis();
        } else {
            // INSTRUKTUR can only see results based on their own jenjang
            $this->data['hasil'] = $this->kuisModel->getCompletedKuis($this->currentUser['jenjang']);
        }

        return view('hasil/index', $this->data);
    }

    public function show($id_kuis)
    {
        $this->requireAuth();

        $this->data['page_title'] = 'Detail Hasil Kuis';
        $this->data['kuis'] = $this->kuisModel->getKuisWithPeserta($id_kuis);

        if (!$this->data['kuis']) {
            return redirect()->to(site_url('hasil'))->with('error', 'Kuis tidak ditemukan');
        }

        $this->data['detail'] = $this->detailKuisModel->getDetailKuisWithSoal($id_kuis);

        // Calculate statistics
        $totalSoal = count($this->data['detail']);
        $totalBenar = 0;
        foreach ($this->data['detail'] as $d) {
            if ($d['is_benar']) {
                $totalBenar++;
            }
        }

        $this->data['statistik'] = [
            'total_soal'  => $totalSoal,
            'total_benar' => $totalBenar,
            'total_salah' => $totalSoal - $totalBenar,
            'persentase'  => $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0,
        ];

        return view('hasil/show', $this->data);
    }

    public function exportPdf($id_kuis)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $kuis = $this->kuisModel->getKuisWithPeserta($id_kuis);
        if (!$kuis) {
            return redirect()->to(site_url('hasil'))->with('error', 'Kuis tidak ditemukan');
        }

        $detail = $this->detailKuisModel->getDetailKuisWithSoal($id_kuis);

        // For now, return a JSON response
        // PDF export can be added later using a library like TCPDF or DomPDF
        return $this->jsonSuccess('Data hasil kuis', [
            'kuis'   => $kuis,
            'detail' => $detail,
        ]);
    }

    public function delete($id_kuis)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $kuis = $this->kuisModel->find($id_kuis);
        if (!$kuis) {
            return redirect()->to(site_url('hasil'))->with('error', 'Kuis tidak ditemukan');
        }

        // Delete details first
        $this->detailKuisModel->deleteByKuis($id_kuis);

        // Delete quiz
        if ($this->kuisModel->delete($id_kuis)) {
            return redirect()->to(site_url('hasil'))->with('success', 'Hasil kuis berhasil dihapus');
        }

        return redirect()->to(site_url('hasil'))->with('error', 'Gagal menghapus hasil kuis');
    }
}
