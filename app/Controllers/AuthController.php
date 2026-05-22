<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class AuthController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    /**
     * Show login form
     */
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            return redirect()->to(site_url('dashboard'));
        }

        // Set page title for login
        $this->data['page_title'] = 'Login';

        return view('auth/login', $this->data);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        // Validate input
        $rules = [
            'nama_pengguna' => 'required|min_length[3]',
            'kata_sandi'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Login gagal. Periksa kembali input Anda.');
        }

        $nama_pengguna = $this->request->getPost('nama_pengguna');
        $kata_sandi     = $this->request->getPost('kata_sandi');

        // Check if username exists
        $user = $this->penggunaModel->where('nama_pengguna', $nama_pengguna)->first();

        if (!$user) {
            // Username tidak ditemukan
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Username tidak ditemukan. Periksa kembali username Anda.');
        }

        // Check if user is active
        if ($user['status'] !== 'AKTIF') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Akun Anda tidak aktif. Hubungi administrator untuk mengaktifkan akun.');
        }

        // Verify password
        if (!password_verify($kata_sandi, $user['kata_sandi'])) {
            // Password salah
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Password salah. Periksa kembali password Anda.');
        }

        // Authentication successful
        // Set session data for compatibility
        $sessionData = [
            'id_pengguna'   => $user['id_pengguna'],
            'nama_pengguna' => $user['nama_pengguna'],
            'nama_lengkap'  => $user['nama_lengkap'],
            'hak_akses'     => $user['hak_akses'],
            'foto_profil'   => $user['foto_profil'],
            'jenjang'       => $user['jenjang'],
            'logged_in'     => true
        ];

        // Set session for BaseController compatibility
        $this->session->set('user', $sessionData);

        // Set individual session variables for view compatibility
        $this->session->set('user_role', $user['hak_akses']);
        $this->session->set('user_id', $user['id_pengguna']);
        $this->session->set('user_name', $user['nama_lengkap']);

        return redirect()->to(site_url('dashboard'))
                       ->with('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
    }

    /**
     * Logout user (GET)
     */
    public function logout()
    {
        if ($this->currentUser) {
            // Clear all session data
            $this->session->remove('user');
            $this->session->remove('user_role');
            $this->session->remove('user_id');
            $this->session->remove('user_name');
        }

        return redirect()->to(site_url('login'))
                       ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Process logout (POST)
     */
    public function processLogout()
    {
        return $this->logout();
    }

    /**
     * Get current authentication status (AJAX/API)
     */
    public function getAuth()
    {
        // Return JSON response for AJAX requests
        if ($this->currentUser) {
            return $this->response->setJSON([
                'status' => 'authenticated',
                'user' => [
                    'id_pengguna'   => $this->currentUser['id_pengguna'],
                    'nama_pengguna' => $this->currentUser['nama_pengguna'],
                    'nama_lengkap'  => $this->currentUser['nama_lengkap'],
                    'hak_akses'     => $this->currentUser['hak_akses'],
                    'foto_profil'   => $this->currentUser['foto_profil'],
                    'jenjang'       => $this->currentUser['jenjang'] ?? null
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'unauthenticated',
                'user' => null
            ]);
        }
    }

    /**
     * Handle authentication login requests (for compatibility)
     */
    public function getAuthenticationLogin()
    {
        // This method handles weird requests from JavaScript
        // Return the same as getAuth for compatibility
        return $this->getAuth();
    }
}
