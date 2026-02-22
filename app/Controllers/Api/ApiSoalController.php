<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SoalModel;
use App\Models\LevelModel;

class ApiSoalController extends ResourceController
{
    protected $soalModel;
    protected $levelModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->levelModel = new LevelModel();
    }

    /**
     * GET /api/soal
     * Get available questions (by level)
     */
    public function index()
    {
        $id_level = $this->request->getGet('level') ?? null;

        if ($id_level) {
            $soal = $this->soalModel->getSoalByLevel($id_level);
        } else {
            $soal = $this->soalModel->getActiveSoal();
        }

        // Remove correct answers from response
        $soalForClient = [];
        foreach ($soal as $s) {
            $level = $this->levelModel->find($s['id_level']);
            $soalForClient[] = [
                'id_soal'     => $s['id_soal'],
                'pertanyaan'  => $s['pertanyaan'],
                'opsi_a'      => $s['opsi_a'],
                'opsi_b'      => $s['opsi_b'],
                'opsi_c'      => $s['opsi_c'],
                'opsi_d'      => $s['opsi_d'],
                'level'       => $level ? $level['nama_level'] : null,
                'id_level'    => $s['id_level'],
            ];
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Daftar soal berhasil diambil',
            'data'    => [
                'soal'       => $soalForClient,
                'total_soal' => count($soalForClient),
            ],
        ])->setStatusCode(200);
    }

    /**
     * GET /api/soal/levels
     * Get all levels
     */
    public function levels()
    {
        $levels = $this->levelModel->getAllOrdered();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Daftar level berhasil diambil',
            'data'    => $levels,
        ])->setStatusCode(200);
    }

    /**
     * GET /api/soal/{id}
     * Get single question (without correct answer)
     */
    public function show($id = null)
    {
        $soal = $this->soalModel->find($id);

        if (!$soal) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Soal tidak ditemukan',
            ])->setStatusCode(404);
        }

        $level = $this->levelModel->find($soal['id_level']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Soal berhasil diambil',
            'data'    => [
                'id_soal'    => $soal['id_soal'],
                'pertanyaan' => $soal['pertanyaan'],
                'opsi_a'     => $soal['opsi_a'],
                'opsi_b'     => $soal['opsi_b'],
                'opsi_c'     => $soal['opsi_c'],
                'opsi_d'     => $soal['opsi_d'],
                'level'      => $level ? $level['nama_level'] : null,
                'id_level'   => $soal['id_level'],
            ],
        ])->setStatusCode(200);
    }

    /**
     * GET /api/soal/random
     * Get random questions
     */
    public function random()
    {
        $jumlah = $this->request->getGet('jumlah') ?? 10;
        $id_level = $this->request->getGet('level') ?? null;

        $soal = $this->soalModel->getRandomSoal($jumlah, $id_level);

        // Remove correct answers
        $soalForClient = [];
        foreach ($soal as $s) {
            $level = $this->levelModel->find($s['id_level']);
            $soalForClient[] = [
                'id_soal'    => $s['id_soal'],
                'pertanyaan' => $s['pertanyaan'],
                'opsi_a'     => $s['opsi_a'],
                'opsi_b'     => $s['opsi_b'],
                'opsi_c'     => $s['opsi_c'],
                'opsi_d'     => $s['opsi_d'],
                'level'      => $level ? $level['nama_level'] : null,
                'id_level'   => $s['id_level'],
            ];
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Soal acak berhasil diambil',
            'data'    => [
                'soal'       => $soalForClient,
                'total_soal' => count($soalForClient),
            ],
        ])->setStatusCode(200);
    }
}
