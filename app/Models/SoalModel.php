<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal';
    protected $primaryKey       = 'id_soal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban_benar',
        'id_level',
        'dibuat_oleh',
        'status',
        'jenjang',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'pertanyaan'    => 'required|min_length[10]',
        'opsi_a'        => 'required|max_length[255]',
        'opsi_b'        => 'required|max_length[255]',
        'opsi_c'        => 'required|max_length[255]',
        'opsi_d'        => 'required|max_length[255]',
        'jawaban_benar' => 'required|in_list[A,B,C,D]',
        'id_level'      => 'required|integer',
        'dibuat_oleh'   => 'required|integer',
        'status'        => 'required|in_list[AKTIF,NONAKTIF]',
        'jenjang'       => 'required|in_list[ELEMENTARY,HIGH_SCHOOL]',
    ];
    protected $validationMessages   = [
        'pertanyaan' => [
            'required'   => 'Pertanyaan harus diisi',
            'min_length' => 'Pertanyaan minimal 10 karakter',
        ],
        'opsi_a' => [
            'required'   => 'Opsi A harus diisi',
            'max_length' => 'Opsi A maksimal 255 karakter',
        ],
        'opsi_b' => [
            'required'   => 'Opsi B harus diisi',
            'max_length' => 'Opsi B maksimal 255 karakter',
        ],
        'opsi_c' => [
            'required'   => 'Opsi C harus diisi',
            'max_length' => 'Opsi C maksimal 255 karakter',
        ],
        'opsi_d' => [
            'required'   => 'Opsi D harus diisi',
            'max_length' => 'Opsi D maksimal 255 karakter',
        ],
        'jawaban_benar' => [
            'required' => 'Jawaban benar harus dipilih',
            'in_list'  => 'Jawaban benar tidak valid',
        ],
        'id_level' => [
            'required' => 'Level harus dipilih',
            'integer'  => 'Level tidak valid',
        ],
        'dibuat_oleh' => [
            'required' => 'Pembuat harus diisi',
            'integer'  => 'Pembuat tidak valid',
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status tidak valid',
        ],
        'jenjang' => [
            'required' => 'Jenjang harus dipilih',
            'in_list'  => 'Jenjang tidak valid',
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
     * Get questions by level
     */
    public function getSoalByLevel($id_level)
    {
        return $this->where('id_level', $id_level)
                    ->where('status', 'AKTIF')
                    ->findAll();
    }

    /**
     * Get random questions (will be shuffled with Fisher-Yates)
     */
    public function getRandomSoal($jumlah, $id_level = null)
    {
        $builder = $this->where('status', 'AKTIF');

        if ($id_level !== null) {
            $builder = $builder->where('id_level', $id_level);
        }

        $allSoal = $builder->findAll();

        // Shuffle using Fisher-Yates (will be done in controller)
        shuffle($allSoal);

        return array_slice($allSoal, 0, $jumlah);
    }

    /**
     * Get active questions only
     */
    public function getActiveSoal()
    {
        return $this->where('status', 'AKTIF')->findAll();
    }

    /**
     * Get active questions by jenjang
     */
    public function getActiveSoalByJenjang($jenjang)
    {
        return $this->where('status', 'AKTIF')
                    ->where('jenjang', $jenjang)
                    ->findAll();
    }

    /**
     * Get question by ID with level info
     */
    public function getSoalWithLevel($id_soal)
    {
        return $this->select('soal.*, level.nama_level, level.deskripsi as level_deskripsi')
                    ->join('level', 'level.id_level = soal.id_level')
                    ->where('soal.id_soal', $id_soal)
                    ->first();
    }

    /**
     * Get all questions with level info
     */
    public function getAllWithLevel($id_level = null)
    {
        $query = $this->select('soal.*, level.nama_level')
                     ->join('level', 'level.id_level = soal.id_level');

        if ($id_level !== null) {
            $query = $query->where('soal.id_level', $id_level);
        }

        return $query->orderBy('soal.waktu_dibuat', 'DESC')->findAll();
    }

    /**
     * Count questions by level
     */
    public function countByLevel($id_level)
    {
        return $this->where('id_level', $id_level)
                    ->where('status', 'AKTIF')
                    ->countAllResults();
    }
}
