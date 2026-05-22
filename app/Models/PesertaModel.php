<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table            = 'peserta';
    protected $primaryKey       = 'id_peserta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'no_hp',
        'token',
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
        'id_peserta'  => 'permit_empty|integer',
        'username'    => 'required|min_length[3]|max_length[50]|is_unique[peserta.username,id_peserta,{id_peserta}]',
        'password'    => 'permit_empty|min_length[6]',
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'email'       => 'required|valid_email|is_unique[peserta.email,id_peserta,{id_peserta}]',
        'no_hp'       => 'permit_empty|max_length[20]',
        'status'      => 'required|in_list[AKTIF,NONAKTIF]',
        'jenjang'     => 'required|in_list[ELEMENTARY,HIGH_SCHOOL]',
    ];
    protected $validationMessages   = [
        'username' => [
            'required'   => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'max_length' => 'Username maksimal 50 karakter',
            'is_unique'  => 'Username sudah digunakan',
        ],
        'password' => [
            'required'   => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter',
        ],
        'nama_lengkap' => [
            'required'   => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
            'max_length' => 'Nama lengkap maksimal 100 karakter',
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique'   => 'Email sudah digunakan',
        ],
        'no_hp' => [
            'max_length' => 'Nomor HP maksimal 20 karakter',
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
    protected $beforeInsert   = ['hashPassword', 'setTimestamps', 'generateToken'];
    protected $beforeUpdate   = ['hashPassword', 'setTimestamps'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        // Check if password is already hashed
        if (isset($data['id']) && strpos($data['data']['password'], '$2y$') === 0) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        if (!isset($data['id'])) {
            $data['data']['waktu_dibuat'] = $currentDateTime;
        }
        $data['data']['waktu_diubah'] = $currentDateTime;

        return $data;
    }

    protected function generateToken(array $data)
    {
        if (!isset($data['data']['token'])) {
            $data['data']['token'] = $this->generateUniqueToken();
        }
        return $data;
    }

    /**
     * Generate unique bearer token
     */
    public function generateUniqueToken()
    {
        do {
            $token = bin2hex(random_bytes(32));
            $exists = $this->where('token', $token)->first();
        } while ($exists !== null);

        return $token;
    }

    /**
     * Reset token for a participant
     */
    public function resetToken($id_peserta)
    {
        return $this->update($id_peserta, [
            'token' => $this->generateUniqueToken(),
        ]);
    }

    /**
     * Validate token and return peserta data
     */
    public function validateToken($token)
    {
        return $this->where('token', $token)
                    ->where('status', 'AKTIF')
                    ->first();
    }

    /**
     * Authenticate and return peserta with token
     */
    public function login($username, $password)
    {
        $peserta = $this->where('username', $username)
                       ->where('status', 'AKTIF')
                       ->first();

        if ($peserta && password_verify($password, $peserta['password'])) {
            // Remove password from response
            unset($peserta['password']);
            return $peserta;
        }

        return null;
    }

    /**
     * Get active participants
     */
    public function getActivePeserta()
    {
        return $this->where('status', 'AKTIF')
                    ->orderBy('waktu_dibuat', 'DESC')
                    ->findAll();
    }

    /**
     * Get participant by username
     */
    public function getByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Get participant by email
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get participant with token (without password)
     */
    public function getPesertaWithToken($id_peserta)
    {
        $peserta = $this->find($id_peserta);
        if ($peserta) {
            unset($peserta['password']);
        }
        return $peserta;
    }
}
