<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KuisModel;
use App\Models\DetailKuisModel;
use App\Models\LevelModel;

class ApiHasilController extends ResourceController
{
    protected $kuisModel;
    protected $detailKuisModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->kuisModel = new KuisModel();
        $this->detailKuisModel = new DetailKuisModel();
    }

    /**
     * Get authenticated peserta from request
     */
    protected function getPeserta()
    {
        return service('request')->peserta ?? null;
    }

    /**
     * GET /api/hasil
     * Get quiz history for current participant
     */
    public function index()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $hasil = $this->kuisModel->getKuisByPeserta($peserta['id_peserta'], 'SELESAI');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Riwayat kuis berhasil diambil',
            'data'    => [
                'hasil'      => $hasil,
                'total_hasil' => count($hasil),
            ],
        ])->setStatusCode(200);
    }

    /**
     * GET /api/hasil/{id_kuis}
     * Get quiz result detail
     */
    public function show($id_kuis = null)
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        // Verify quiz belongs to this peserta
        $kuis = $this->kuisModel->find($id_kuis);
        if (!$kuis || $kuis['id_peserta'] != $peserta['id_peserta']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis tidak ditemukan',
            ])->setStatusCode(404);
        }

        $kuis = $this->kuisModel->getKuisWithPeserta($id_kuis);
        $detail = $this->detailKuisModel->getDetailKuisWithSoal($id_kuis);

        // Calculate statistics
        $totalSoal = count($detail);
        $totalBenar = 0;
        foreach ($detail as $d) {
            if ($d['is_benar']) {
                $totalBenar++;
            }
        }

        $statistik = [
            'total_soal'  => $totalSoal,
            'total_benar' => $totalBenar,
            'total_salah' => $totalSoal - $totalBenar,
            'persentase'  => $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0,
        ];

        $levelModel = new LevelModel();
        $allLevels = $levelModel->findAll();
        $waktuPengerjaan = array_sum(array_column($allLevels, 'waktu_pengerjaan'));

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Detail hasil kuis berhasil diambil',
            'data'    => [
                'kuis'             => $kuis,
                'detail'           => $detail,
                'statistik'        => $statistik,
                'waktu_pengerjaan' => $waktuPengerjaan,
            ],
        ])->setStatusCode(200);
    }

    /**
     * GET /api/hasil/latest
     * Get latest quiz result
     */
    public function latest()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $hasil = $this->kuisModel->getKuisByPeserta($peserta['id_peserta'], 'SELESAI');

        if (empty($hasil)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Belum ada riwayat kuis',
                'data'    => null,
            ])->setStatusCode(200);
        }

        $latestKuis = $hasil[0]; // First one is the latest due to ORDER BY DESC
        $detail = $this->detailKuisModel->getDetailKuisWithSoal($latestKuis['id_kuis']);

        // Calculate statistics
        $totalSoal = count($detail);
        $totalBenar = 0;
        foreach ($detail as $d) {
            if ($d['is_benar']) {
                $totalBenar++;
            }
        }

        $statistik = [
            'total_soal'  => $totalSoal,
            'total_benar' => $totalBenar,
            'total_salah' => $totalSoal - $totalBenar,
            'persentase'  => $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0,
        ];

        $levelModel = new LevelModel();
        $allLevels = $levelModel->findAll();
        $waktuPengerjaan = array_sum(array_column($allLevels, 'waktu_pengerjaan'));

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Hasil kuis terbaru berhasil diambil',
            'data'    => [
                'kuis'             => $latestKuis,
                'detail'           => $detail,
                'statistik'        => $statistik,
                'waktu_pengerjaan' => $waktuPengerjaan,
            ],
        ])->setStatusCode(200);
    }

    /**
     * GET /api/hasil/statistics
     * Get overall statistics for participant
     */
    public function statistics()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $hasil = $this->kuisModel->getKuisByPeserta($peserta['id_peserta'], 'SELESAI');

        $totalKuis = count($hasil);
        $totalNilai = 0;
        $levelCounts = [
            'BEGINNER'     => 0,
            'INTERMEDIATE' => 0,
            'ADVANCED'     => 0,
        ];

        foreach ($hasil as $h) {
            $totalNilai += $h['total_nilai'];
            if (isset($levelCounts[$h['level_ditetapkan']])) {
                $levelCounts[$h['level_ditetapkan']]++;
            }
        }

        $rataRata = $totalKuis > 0 ? round($totalNilai / $totalKuis) : 0;

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Statistik berhasil diambil',
            'data'    => [
                'total_kuis'  => $totalKuis,
                'rata_rata'   => $rataRata,
                'level_counts' => $levelCounts,
            ],
        ])->setStatusCode(200);
    }
}
