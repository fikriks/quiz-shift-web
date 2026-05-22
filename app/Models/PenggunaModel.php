<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table            = 'pengguna';
    protected $primaryKey       = 'id_pengguna';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_pengguna',
        'kata_sandi',
        'nama_lengkap',
        'hak_akses',
        'foto_profil',
        'status',
        'jenjang'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'id_pengguna'   => 'permit_empty|integer',
        'nama_pengguna' => 'required|min_length[3]|max_length[50]|is_unique[pengguna.nama_pengguna,id_pengguna,{id_pengguna}]',
        'kata_sandi'    => 'required|min_length[6]',
        'nama_lengkap'  => 'required|min_length[3]|max_length[100]',
        'hak_akses'     => 'required|in_list[ADMIN,INSTRUKTUR]',
        'status'        => 'required|in_list[AKTIF,NONAKTIF]',
        'foto_profil'   => 'permit_empty|max_size[foto_profil,2048]',
        'jenjang'       => 'permit_empty|in_list[ELEMENTARY,HIGH_SCHOOL]'
    ];
    protected $validationMessages   = [
        'nama_pengguna' => [
            'required'      => 'Nama pengguna harus diisi',
            'min_length'    => 'Nama pengguna minimal 3 karakter',
            'max_length'    => 'Nama pengguna maksimal 50 karakter',
            'is_unique'     => 'Nama pengguna sudah digunakan'
        ],
        'kata_sandi' => [
            'required'      => 'Kata sandi harus diisi',
            'min_length'    => 'Kata sandi minimal 6 karakter'
        ],
        'nama_lengkap' => [
            'required'      => 'Nama lengkap harus diisi',
            'min_length'    => 'Nama lengkap minimal 3 karakter',
            'max_length'    => 'Nama lengkap maksimal 100 karakter'
        ],
        'hak_akses' => [
            'required'      => 'Hak akses harus dipilih',
            'in_list'       => 'Hak akses tidak valid'
        ],
        'status' => [
            'required'      => 'Status harus dipilih',
            'in_list'       => 'Status tidak valid'
        ],
        'foto_profil' => [
            'max_size'      => 'Ukuran foto maksimal 2MB',
            'is_image'      => 'File harus berupa gambar (JPG, PNG, GIF)'
        ],
        'jenjang' => [
            'in_list'       => 'Jenjang tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword', 'setTimestamps'];
    protected $beforeUpdate   = ['hashPassword', 'setTimestamps'];

    // Custom methods
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['kata_sandi'])) {
            return $data;
        }

        // Hash password jika bukan update tanpa mengubah password
        // Check if the password is already hashed (starts with $2y$)
        if (isset($data['id']) && strpos($data['data']['kata_sandi'], '$2y$') === 0) {
            return $data;
        }

        $data['data']['kata_sandi'] = password_hash($data['data']['kata_sandi'], PASSWORD_DEFAULT);
        return $data;
    }

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        if (!isset($data['id'])) {
            // Insert
            $data['data']['waktu_dibuat'] = $currentDateTime;
        }
        $data['data']['waktu_diubah'] = $currentDateTime;

        return $data;
    }

    protected function getPasswordHash($id)
    {
        // Handle case where id is an array
        if (is_array($id)) {
            $id = $id[0] ?? null;
        }

        if (!$id) {
            return null;
        }

        $user = $this->find($id);
        return $user ? $user['kata_sandi'] : null;
    }

    // Authentication methods
    public function authenticate($nama_pengguna, $kata_sandi)
    {
        $user = $this->where('nama_pengguna', $nama_pengguna)
                     ->where('status', 'AKTIF')
                     ->first();

        if ($user && password_verify($kata_sandi, $user['kata_sandi'])) {
            return $user;
        }

        return null;
    }


    public function getActiveUsers($hak_akses = null)
    {
        $builder = $this->where('status', 'AKTIF');

        if ($hak_akses) {
            $builder = $builder->where('hak_akses', $hak_akses);
        }

        return $builder->findAll();
    }
}
