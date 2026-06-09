<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\FisherYates;
use App\Models\KuisModel;
use App\Models\DetailKuisModel;
use App\Models\SoalModel;
use App\Models\LevelModel;

class ApiKuisController extends ResourceController
{
    protected $kuisModel;
    protected $detailKuisModel;
    protected $soalModel;
    protected $fisherYates;
    protected $format = 'json';

    public function __construct()
    {
        $this->kuisModel = new KuisModel();
        $this->detailKuisModel = new DetailKuisModel();
        $this->soalModel = new SoalModel();
        $this->fisherYates = new FisherYates();
    }

    /**
     * Get authenticated peserta from request
     */
    protected function getPeserta()
    {
        return service('request')->peserta ?? null;
    }

    /**
     * POST /api/kuis/start
     * Start new quiz session with shuffled questions
     */
    public function start()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        // Check if there's an active quiz
        $activeKuis = $this->kuisModel->getActiveKuis($peserta['id_peserta']);

        $levelModel = new LevelModel();
        $allLevels = $levelModel->findAll();
        $totalWaktuPengerjaan = array_sum(array_column($allLevels, 'waktu_pengerjaan'));

        if ($activeKuis) {
            // Get questions for the active quiz from detail_kuis
            $detailKuis = $this->detailKuisModel->getDetailKuisWithSoal($activeKuis['id_kuis']);

            // Format questions for client
            $soalForClient = [];
            foreach ($detailKuis as $detail) {
                $soalForClient[] = [
                    'id_soal'     => $detail['id_soal'],
                    'pertanyaan'  => $detail['pertanyaan'],
                    'opsi_a'      => $detail['opsi_a'],
                    'opsi_b'      => $detail['opsi_b'],
                    'opsi_c'      => $detail['opsi_c'],
                    'opsi_d'      => $detail['opsi_d'],
                    'id_level'    => $detail['id_level'],
                    'level'       => $detail['nama_level'],
                    'urutan_soal' => $detail['urutan_soal'],
                ];
            }

            // Sort by urutan_soal
            usort($soalForClient, function($a, $b) {
                return $a['urutan_soal'] - $b['urutan_soal'];
            });

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Melanjutkan kuis yang sedang berlangsung',
                'data'    => [
                    'id_kuis'     => $activeKuis['id_kuis'],
                    'nama_kuis'   => $activeKuis['nama_kuis'],
                    'soal'        => $soalForClient,
                    'total_soal'  => count($soalForClient),
                    'waktu_mulai' => $activeKuis['waktu_mulai'],
                    'status'      => $activeKuis['status'],
                    'waktu_pengerjaan' => $totalWaktuPengerjaan,
                ],
            ])->setStatusCode(200);
        }

        // Get all active questions filtered by participant's jenjang
        $allSoal = $this->soalModel->getActiveSoalByJenjang($peserta['jenjang']);

        if (empty($allSoal)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Belum ada soal tersedia untuk jenjang Anda',
            ])->setStatusCode(400);
        }

        // Shuffle questions using Fisher-Yates algorithm
        $shuffledSoal = $this->fisherYates->shuffleWithOrder($allSoal);

        // Remove correct answers from questions sent to client
        $soalForClient = [];
        foreach ($shuffledSoal as $soal) {
            $soalForClient[] = [
                'id_soal'     => $soal['id_soal'],
                'pertanyaan'  => $soal['pertanyaan'],
                'opsi_a'      => $soal['opsi_a'],
                'opsi_b'      => $soal['opsi_b'],
                'opsi_c'      => $soal['opsi_c'],
                'opsi_d'      => $soal['opsi_d'],
                'id_level'    => $soal['id_level'],
                'urutan_soal' => $soal['urutan_soal'],
            ];
        }

        // Create new quiz session
        $id_kuis = $this->kuisModel->createKuis($peserta['id_peserta']);

        // Populate detail_kuis with shuffled questions
        // Skip validation since we're only creating quiz structure, not saving answers yet
        $this->detailKuisModel->skipValidation(true);
        foreach ($shuffledSoal as $index => $soal) {
            $this->detailKuisModel->insert([
                'id_kuis'     => $id_kuis,
                'id_soal'     => $soal['id_soal'],
                'urutan_soal' => $soal['urutan_soal'],
            ]);
        }
        $this->detailKuisModel->skipValidation(false);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis baru dimulai',
            'data'    => [
                'id_kuis' => $id_kuis,
                'soal'    => $soalForClient,
                'total_soal' => count($soalForClient),
                'waktu_pengerjaan' => $totalWaktuPengerjaan,
            ],
        ])->setStatusCode(201);
    }

    /**
     * POST /api/kuis/submit
     * Submit answer for a question
     */
    public function submit()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $id_kuis = $json->id_kuis ?? null;
        $id_soal = $json->id_soal ?? null;
        $jawaban = $json->jawaban ?? null;
        $urutan_soal = $json->urutan_soal ?? null;

        $rules = [
            'id_kuis'       => 'required|integer',
            'id_soal'       => 'required|integer',
            'jawaban'       => 'required|in_list[A,B,C,D]',
            'urutan_soal'   => 'required|integer',
        ];

        if (!$id_kuis || !$id_soal || !$jawaban || $urutan_soal === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak lengkap',
            ])->setStatusCode(422);
        }

        // Verify quiz belongs to this peserta
        $kuis = $this->kuisModel->find($id_kuis);
        if (!$kuis || $kuis['id_peserta'] != $peserta['id_peserta']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Check if quiz is still active
        if ($kuis['status'] !== 'BERLANGSUNG') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis sudah selesai atau dibatalkan',
            ])->setStatusCode(400);
        }

        // Check if question already answered
        if ($this->detailKuisModel->isAnswered($id_kuis, $id_soal)) {
            // Update existing answer
            $updated = $this->detailKuisModel->updateJawaban($id_kuis, $id_soal, $jawaban);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Jawaban diperbarui',
            ])->setStatusCode(200);
        }

        // Save new answer
        $data = [
            'id_kuis'     => $id_kuis,
            'id_soal'     => $id_soal,
            'jawaban_siswa' => $jawaban,
            'urutan_soal' => $urutan_soal,
        ];

        $id_detail = $this->detailKuisModel->saveJawaban($data);

        if ($id_detail) {
            $detail = $this->detailKuisModel->find($id_detail);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Jawaban berhasil disimpan',
                'data'    => [
                    'is_benar' => (bool) $detail['is_benar'],
                ],
            ])->setStatusCode(201);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menyimpan jawaban',
        ])->setStatusCode(500);
    }

    /**
     * POST /api/kuis/finish
     * Finish quiz and get results
     */
    public function finish()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $id_kuis = $json->id_kuis ?? null;

        if (!$id_kuis) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID kuis wajib diisi',
            ])->setStatusCode(422);
        }

        // Verify quiz belongs to this peserta
        $kuis = $this->kuisModel->find($id_kuis);
        if (!$kuis || $kuis['id_peserta'] != $peserta['id_peserta']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Check if quiz is still active
        if ($kuis['status'] !== 'BERLANGSUNG') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis sudah selesai atau dibatalkan',
            ])->setStatusCode(400);
        }

        // Finish quiz and calculate score
        $result = $this->kuisModel->finishKuis($id_kuis);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis selesai',
            'data'    => $result,
        ])->setStatusCode(200);
    }

    /**
     * GET /api/kuis/active
     * Get active quiz for current participant
     */
    public function active()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $activeKuis = $this->kuisModel->getActiveKuis($peserta['id_peserta']);

        if (!$activeKuis) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Tidak ada kuis yang sedang berlangsung',
                'data'    => null,
            ])->setStatusCode(200);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis aktif ditemukan',
            'data'    => [
                'id_kuis'     => $activeKuis['id_kuis'],
                'nama_kuis'   => $activeKuis['nama_kuis'],
                'waktu_mulai' => $activeKuis['waktu_mulai'],
                'status'      => $activeKuis['status'],
            ],
        ])->setStatusCode(200);
    }

    /**
     * POST /api/kuis/cancel
     * Cancel active quiz
     */
    public function cancel()
    {
        $peserta = $this->getPeserta();

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $id_kuis = $json->id_kuis ?? null;

        if (!$id_kuis) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID kuis wajib diisi',
            ])->setStatusCode(422);
        }

        // Verify quiz belongs to this peserta
        $kuis = $this->kuisModel->find($id_kuis);
        if (!$kuis || $kuis['id_peserta'] != $peserta['id_peserta']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kuis tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Cancel quiz
        $this->kuisModel->cancelKuis($id_kuis);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis berhasil dibatalkan',
        ])->setStatusCode(200);
    }
}
