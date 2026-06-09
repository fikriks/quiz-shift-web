<?php

namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
    protected $table            = 'level';
    protected $primaryKey       = 'id_level';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_level',
        'deskripsi',
        'nilai_min',
        'nilai_max',
        'waktu_pengerjaan',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'nama_level' => 'required|in_list[BEGINNER,INTERMEDIATE,ADVANCED]',
        'nilai_min'  => 'required|integer|greater_than_equal_to[0]',
        'nilai_max'  => 'required|integer|greater_than_equal_to[0]',
        'waktu_pengerjaan' => 'required|integer|greater_than[0]',
    ];
    protected $validationMessages   = [
        'nama_level' => [
            'required' => 'Nama level harus diisi',
            'in_list'  => 'Nama level tidak valid',
        ],
        'nilai_min' => [
            'required'             => 'Nilai minimum harus diisi',
            'integer'              => 'Nilai minimum harus berupa angka',
            'greater_than_equal_to' => 'Nilai minimum tidak boleh negatif',
        ],
        'nilai_max' => [
            'required'             => 'Nilai maksimum harus diisi',
            'integer'              => 'Nilai maksimum harus berupa angka',
            'greater_than_equal_to' => 'Nilai maksimum tidak boleh negatif',
        ],
        'waktu_pengerjaan' => [
            'required'      => 'Waktu pengerjaan harus diisi',
            'integer'       => 'Waktu pengerjaan harus berupa angka',
            'greater_than'  => 'Waktu pengerjaan harus lebih dari 0',
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
     * Get level by score
     */
    public function getLevelByScore($nilai)
    {
        return $this->where('nilai_min <=', $nilai)
                    ->where('nilai_max >=', $nilai)
                    ->first();
    }

    /**
     * Get level by name
     */
    public function getLevelByName($nama_level)
    {
        return $this->where('nama_level', $nama_level)->first();
    }

    /**
     * Get all active levels ordered by score range
     */
    public function getAllOrdered()
    {
        return $this->orderBy('nilai_min', 'ASC')->findAll();
    }
}
