<?php

namespace App\Controllers;

use App\Models\PenggunaModel;
use App\Models\SoalModel;

class PenggunaController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    public function index()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $this->data['page_title'] = 'Daftar Pengguna';
        // Only list INSTRUKTUR, hide ADMIN users
        $this->data['pengguna'] = $this->penggunaModel->where('hak_akses', 'INSTRUKTUR')->findAll();

        return view('pengguna/index', $this->data);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $this->data['page_title'] = 'Tambah Instruktur';

        return view('pengguna/form', $this->data);
    }

    public function store()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'kata_sandi'    => $this->request->getPost('kata_sandi'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => 'INSTRUKTUR', // Forced to INSTRUKTUR
            'status'        => 'AKTIF', // Default value on insert
            'foto_profil'   => null,
            'jenjang'       => $this->request->getPost('jenjang'),
        ];

        if ($this->penggunaModel->insert($data)) {
            return redirect()->to(site_url('pengguna'))->with('success', 'Instruktur berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan instruktur')
                                   ->with('errors', $this->penggunaModel->errors())
                                   ->withInput();
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $pengguna = $this->penggunaModel->find($id);

        // Security check: Only allow editing INSTRUKTUR, protect ADMIN
        if (!$pengguna || $pengguna['hak_akses'] !== 'INSTRUKTUR') {
            return redirect()->to(site_url('pengguna'))->with('error', 'Pengguna tidak ditemukan atau tidak dapat diedit');
        }

        $this->data['page_title'] = 'Edit Instruktur';
        $this->data['pengguna'] = $pengguna;

        return view('pengguna/form', $this->data);
    }

    public function update($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $pengguna = $this->penggunaModel->find($id);

        // Security check: Only allow updating INSTRUKTUR, protect ADMIN
        if (!$pengguna || $pengguna['hak_akses'] !== 'INSTRUKTUR') {
            return redirect()->to(site_url('pengguna'))->with('error', 'Pengguna tidak ditemukan atau tidak dapat diperbarui');
        }

        $data = [
            'id_pengguna'   => $id, // Penting untuk is_unique[...,id_pengguna,{id_pengguna}]
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'status'        => $this->request->getPost('status'),
            'hak_akses'     => 'INSTRUKTUR', // Forced to prevent role tampering
            'jenjang'       => $this->request->getPost('jenjang'),
        ];

        // Handle password update optionally
        $kata_sandi = $this->request->getPost('kata_sandi');
        if (!empty($kata_sandi)) {
            $data['kata_sandi'] = $kata_sandi;
        } else {
            // Password not provided, change rule to permit_empty
            $this->penggunaModel->setValidationRule('kata_sandi', 'permit_empty|min_length[6]');
        }

        if ($this->penggunaModel->update($id, $data)) {
            return redirect()->to(site_url('pengguna'))->with('success', 'Instruktur berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui instruktur')
                                   ->with('errors', $this->penggunaModel->errors())
                                   ->withInput();
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        // Defense-in-depth safety check
        if (!$this->currentUser || $this->currentUser['hak_akses'] !== 'ADMIN') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }

        $pengguna = $this->penggunaModel->find($id);

        // Security check: Only allow deleting INSTRUKTUR, protect ADMIN
        if (!$pengguna || $pengguna['hak_akses'] !== 'INSTRUKTUR') {
            return redirect()->to(site_url('pengguna'))->with('error', 'Pengguna tidak ditemukan atau tidak dapat dihapus');
        }

        // Check if instructor has created any questions (soal)
        $soalModel = new SoalModel();
        $soalCount = $soalModel->where('dibuat_oleh', $id)->countAllResults();

        if ($soalCount > 0) {
            return redirect()->to(site_url('pengguna'))->with('error', "Instruktur tidak dapat dihapus karena telah membuat {$soalCount} soal");
        }

        if ($this->penggunaModel->delete($id)) {
            return redirect()->to(site_url('pengguna'))->with('success', 'Instruktur berhasil dihapus');
        }

        return redirect()->to(site_url('pengguna'))->with('error', 'Gagal menghapus instruktur');
    }
}
