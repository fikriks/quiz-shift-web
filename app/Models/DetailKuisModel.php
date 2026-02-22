<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailKuisModel extends Model
{
    protected $table            = 'detail_kuis';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kuis',
        'id_soal',
        'jawaban_siswa',
        'is_benar',
        'urutan_soal',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';

    // Validation
    protected $validationRules      = [
        'id_kuis'       => 'required|integer',
        'id_soal'       => 'required|integer',
        'jawaban_siswa' => 'required|in_list[A,B,C,D]',
        'is_benar'      => 'required|in_list[0,1]',
        'urutan_soal'   => 'required|integer',
    ];
    protected $validationMessages   = [
        'id_kuis' => [
            'required' => 'Kuis harus dipilih',
            'integer'  => 'Kuis tidak valid',
        ],
        'id_soal' => [
            'required' => 'Soal harus dipilih',
            'integer'  => 'Soal tidak valid',
        ],
        'jawaban_siswa' => [
            'required' => 'Jawaban siswa harus diisi',
            'in_list'  => 'Jawaban siswa tidak valid',
        ],
        'is_benar' => [
            'required' => 'Status benar harus ditentukan',
            'in_list'  => 'Status benar tidak valid',
        ],
        'urutan_soal' => [
            'required' => 'Urutan soal harus diisi',
            'integer'  => 'Urutan soal tidak valid',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamp'];

    protected function setTimestamp(array $data)
    {
        $data['data']['waktu_dibuat'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Save student's answer
     */
    public function saveJawaban($data)
    {
        // Get the correct answer for the question
        $soalModel = new \App\Models\SoalModel();
        $soal = $soalModel->find($data['id_soal']);

        if (!$soal) {
            return false;
        }

        // Determine if answer is correct
        $isBenar = ($data['jawaban_siswa'] === $soal['jawaban_benar']);

        $data['is_benar'] = $isBenar ? 1 : 0;

        return $this->insert($data);
    }

    /**
     * Get quiz details
     */
    public function getDetailKuis($id_kuis)
    {
        return $this->select('detail_kuis.*, soal.pertanyaan, soal.opsi_a, soal.opsi_b, soal.opsi_c, soal.opsi_d, soal.jawaban_benar')
                    ->join('soal', 'soal.id_soal = detail_kuis.id_soal')
                    ->where('detail_kuis.id_kuis', $id_kuis)
                    ->orderBy('detail_kuis.urutan_soal', 'ASC')
                    ->findAll();
    }

    /**
     * Get quiz details with question info
     */
    public function getDetailKuisWithSoal($id_kuis)
    {
        return $this->select('detail_kuis.*, soal.pertanyaan, soal.opsi_a, soal.opsi_b, soal.opsi_c, soal.opsi_d, soal.jawaban_benar, soal.id_level, level.nama_level')
                    ->join('soal', 'soal.id_soal = detail_kuis.id_soal')
                    ->join('level', 'level.id_level = soal.id_level')
                    ->where('detail_kuis.id_kuis', $id_kuis)
                    ->orderBy('detail_kuis.urutan_soal', 'ASC')
                    ->findAll();
    }

    /**
     * Check if question is already answered
     */
    public function isAnswered($id_kuis, $id_soal)
    {
        return $this->where('id_kuis', $id_kuis)
                    ->where('id_soal', $id_soal)
                    ->first() !== null;
    }

    /**
     * Get answered questions count for quiz
     */
    public function getAnsweredCount($id_kuis)
    {
        return $this->where('id_kuis', $id_kuis)->countAllResults();
    }

    /**
     * Get correct answers count for quiz
     */
    public function getCorrectCount($id_kuis)
    {
        return $this->where('id_kuis', $id_kuis)
                    ->where('is_benar', 1)
                    ->countAllResults();
    }

    /**
     * Update answer for a question
     */
    public function updateJawaban($id_kuis, $id_soal, $jawaban_siswa)
    {
        $soalModel = new \App\Models\SoalModel();
        $soal = $soalModel->find($id_soal);

        if (!$soal) {
            return false;
        }

        $isBenar = ($jawaban_siswa === $soal['jawaban_benar']);

        return $this->where('id_kuis', $id_kuis)
                    ->where('id_soal', $id_soal)
                    ->set([
                        'jawaban_siswa' => $jawaban_siswa,
                        'is_benar'      => $isBenar ? 1 : 0,
                    ])
                    ->update();
    }

    /**
     * Delete all answers for a quiz
     */
    public function deleteByKuis($id_kuis)
    {
        return $this->where('id_kuis', $id_kuis)->delete();
    }
}
