<?php

namespace App\Models;

use CodeIgniter\Model;

class KuisModel extends Model
{
    protected $table            = 'kuis';
    protected $primaryKey       = 'id_kuis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kuis',
        'id_peserta',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'total_nilai',
        'level_ditetapkan',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'nama_kuis'       => 'required|max_length[100]',
        'id_peserta'      => 'required|integer',
        'status'          => 'required|in_list[BERLANGSUNG,SELESAI,DIBATALKAN]',
        'level_ditetapkan' => 'permit_empty|in_list[BEGINNER,INTERMEDIATE,ADVANCED]',
    ];
    protected $validationMessages   = [
        'nama_kuis' => [
            'required'   => 'Nama kuis harus diisi',
            'max_length' => 'Nama kuis maksimal 100 karakter',
        ],
        'id_peserta' => [
            'required' => 'Peserta harus dipilih',
            'integer'  => 'Peserta tidak valid',
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status tidak valid',
        ],
        'level_ditetapkan' => [
            'in_list' => 'Level yang ditetapkan tidak valid',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamps'];
    protected $beforeUpdate   = ['setTimestamps'];

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        if (!isset($data['id'])) {
            $data['data']['waktu_dibuat'] = $currentDateTime;
        }
        $data['data']['waktu_diubah'] = $currentDateTime;

        return $data;
    }

    /**
     * Create new quiz session
     */
    public function createKuis($id_peserta, $nama_kuis = null)
    {
        $nama_kuis = $nama_kuis ?? 'Kuis_' . date('Y-m-d_H-i-s');

        return $this->insert([
            'nama_kuis'  => $nama_kuis,
            'id_peserta' => $id_peserta,
            'waktu_mulai' => date('Y-m-d H:i:s'),
            'status'     => 'BERLANGSUNG',
        ]);
    }

    /**
     * Finish quiz and calculate score
     */
    public function finishKuis($id_kuis)
    {
        $detailKuisModel = new \App\Models\DetailKuisModel();

        // Get all answers for this quiz
        $answers = $detailKuisModel->where('id_kuis', $id_kuis)->findAll();

        // Calculate score
        $totalBenar = 0;
        foreach ($answers as $answer) {
            if ($answer['is_benar']) {
                $totalBenar++;
            }
        }

        // Calculate percentage score
        $totalSoal = count($answers);
        $nilai = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0;

        // Get level based on score
        $levelModel = new \App\Models\LevelModel();
        $level = $levelModel->getLevelByScore($nilai);
        $namaLevel = $level ? $level['nama_level'] : null;

        // Update quiz
        $this->update($id_kuis, [
            'waktu_selesai'    => date('Y-m-d H:i:s'),
            'status'           => 'SELESAI',
            'total_nilai'      => $nilai,
            'level_ditetapkan' => $namaLevel,
        ]);

        return [
            'nilai'       => $nilai,
            'total_benar' => $totalBenar,
            'total_soal'  => $totalSoal,
            'level'       => $namaLevel,
        ];
    }

    /**
     * Get quiz by participant
     */
    public function getKuisByPeserta($id_peserta, $status = null)
    {
        $builder = $this->where('id_peserta', $id_peserta);

        if ($status !== null) {
            $builder = $builder->where('status', $status);
        }

        return $builder->orderBy('waktu_dibuat', 'DESC')->findAll();
    }

    /**
     * Get active quiz for participant
     */
    public function getActiveKuis($id_peserta)
    {
        return $this->where('id_peserta', $id_peserta)
                    ->where('status', 'BERLANGSUNG')
                    ->first();
    }

    /**
     * Get quiz with participant details
     */
    public function getKuisWithPeserta($id_kuis)
    {
        return $this->select('kuis.*, peserta.nama_lengkap, peserta.email')
                    ->join('peserta', 'peserta.id_peserta = kuis.id_peserta')
                    ->where('kuis.id_kuis', $id_kuis)
                    ->first();
    }

    /**
     * Get all completed quizzes
     */
    public function getCompletedKuis()
    {
        return $this->select('kuis.*, peserta.nama_lengkap, peserta.email')
                    ->join('peserta', 'peserta.id_peserta = kuis.id_peserta')
                    ->where('kuis.status', 'SELESAI')
                    ->orderBy('kuis.waktu_selesai', 'DESC')
                    ->findAll();
    }

    /**
     * Cancel quiz
     */
    public function cancelKuis($id_kuis)
    {
        return $this->update($id_kuis, [
            'status'      => 'DIBATALKAN',
            'waktu_selesai' => date('Y-m-d H:i:s'),
        ]);
    }
}
