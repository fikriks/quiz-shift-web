<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PesertaModel;

class ApiAuthController extends ResourceController
{
    protected $pesertaModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->pesertaModel = new PesertaModel();
    }

    /**
     * POST /api/auth/login
     * Authenticate participant and return bearer token
     */
    public function login()
    {
        $json = $this->request->getJSON();
        $username = $json->username ?? null;
        $password = $json->password ?? null;

        if (!$username || !$password) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Username dan password wajib diisi',
            ])->setStatusCode(422);
        }

        $peserta = $this->pesertaModel->login($username, $password);

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Username atau password salah',
            ])->setStatusCode(401);
        }

        // Return token and peserta data
        unset($peserta['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => [
                'token'   => $peserta['token'],
                'peserta' => $peserta,
            ],
        ])->setStatusCode(200);
    }

    /**
     * POST /api/auth/logout
     * Logout (optional - token invalidation can be implemented later)
     */
    public function logout()
    {
        // For stateless JWT-like tokens, logout is handled client-side
        // by removing the token. If we want server-side invalidation,
        // we can implement token blacklisting.

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil',
        ])->setStatusCode(200);
    }

    /**
     * GET /api/auth/me
     * Get current authenticated participant
     */
    public function me()
    {
        // Get peserta from request (set by ApiAuthFilter)
        $peserta = service('request')->peserta ?? null;

        if (!$peserta) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak terautentikasi',
            ])->setStatusCode(401);
        }

        unset($peserta['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data peserta berhasil diambil',
            'data'    => $peserta,
        ])->setStatusCode(200);
    }

    /**
     * POST /api/auth/register
     * Register new participant (optional feature)
     */
    public function register()
    {
        $rules = [
            'username'     => 'required|min_length[3]|max_length[50]|is_unique[peserta.username]',
            'password'     => 'required|min_length[6]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email'        => 'required|valid_email|is_unique[peserta.email]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
            ])->setStatusCode(422);
        }

        $data = [
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'no_hp'        => $this->request->getPost('no_hp') ?: null,
            'status'       => 'AKTIF',
        ];

        try {
            $id_peserta = $this->pesertaModel->insert($data);

            if ($id_peserta) {
                $peserta = $this->pesertaModel->getPesertaWithToken($id_peserta);

                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Registrasi berhasil',
                    'data'    => [
                        'token'   => $peserta['token'],
                        'peserta' => $peserta,
                    ],
                ])->setStatusCode(201);
            }

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal melakukan registrasi',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
