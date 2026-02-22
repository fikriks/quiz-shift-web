<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\FisherYates;
use App\Models\KuisModel;
use App\Models\DetailKuisModel;
use App\Models\SoalModel;

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

        if ($activeKuis) {
            // Return existing active quiz
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Melanjutkan kuis yang sedang berlangsung',
                'data'    => [
                    'id_kuis'     => $activeKuis['id_kuis'],
                    'nama_kuis'   => $activeKuis['nama_kuis'],
                    'waktu_mulai' => $activeKuis['waktu_mulai'],
                    'status'      => $activeKuis['status'],
                ],
            ])->setStatusCode(200);
        }

        // Get all active questions
        $allSoal = $this->soalModel->getActiveSoal();

        if (empty($allSoal)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Belum ada soal tersedia',
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

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis baru dimulai',
            'data'    => [
                'id_kuis' => $id_kuis,
                'soal'    => $soalForClient,
                'total_soal' => count($soalForClient),
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

        $rules = [
            'id_kuis'       => 'required|integer',
            'id_soal'       => 'required|integer',
            'jawaban'       => 'required|in_list[A,B,C,D]',
            'urutan_soal'   => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ])->setStatusCode(422);
        }

        $id_kuis = $this->request->getPost('id_kuis');
        $id_soal = $this->request->getPost('id_soal');
        $jawaban = $this->request->getPost('jawaban');
        $urutan_soal = $this->request->getPost('urutan_soal');

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

        $rules = [
            'id_kuis' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ])->setStatusCode(422);
        }

        $id_kuis = $this->request->getPost('id_kuis');

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

        $rules = [
            'id_kuis' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ])->setStatusCode(422);
        }

        $id_kuis = $this->request->getPost('id_kuis');

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
