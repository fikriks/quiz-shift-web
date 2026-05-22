<?php

namespace App\Controllers;

use App\Models\PesertaModel;

class PesertaController extends BaseController
{
    protected $pesertaModel;

    public function __construct()
    {
        $this->pesertaModel = new PesertaModel();
    }

    public function index()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $this->data['page_title'] = 'Daftar Peserta';
        $this->data['peserta'] = $this->pesertaModel->getActivePeserta();

        // Hide passwords from view
        foreach ($this->data['peserta'] as &$p) {
            unset($p['password']);
        }

        return view('peserta/index', $this->data);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $this->data['page_title'] = 'Tambah Peserta Baru';

        return view('peserta/form', $this->data);
    }

    public function store()
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $data = [
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'no_hp'        => $this->request->getPost('no_hp') ?: null,
            'status'       => 'AKTIF',
            'jenjang'      => $this->request->getPost('jenjang'),
        ];

        // Ensure password is required for store
        $this->pesertaModel->setValidationRule('password', 'required|min_length[6]');

        if ($this->pesertaModel->insert($data)) {
            return redirect()->to(site_url('peserta'))->with('success', 'Peserta berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan peserta')
                                   ->with('errors', $this->pesertaModel->errors());
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $this->data['page_title'] = 'Edit Peserta';
        $this->data['peserta'] = $this->pesertaModel->getPesertaWithToken($id);

        if (!$this->data['peserta']) {
            return redirect()->to(site_url('peserta'))->with('error', 'Peserta tidak ditemukan');
        }

        return view('peserta/form', $this->data);
    }

    public function update($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $data = [
            'id_peserta'   => $id, // Penting untuk is_unique[...,id_peserta,{id_peserta}]
            'username'     => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'no_hp'        => $this->request->getPost('no_hp') ?: null,
            'status'       => $this->request->getPost('status'),
            'jenjang'      => $this->request->getPost('jenjang'),
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($this->pesertaModel->update($id, $data)) {
            return redirect()->to(site_url('peserta'))->with('success', 'Peserta berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui peserta')
                                   ->with('errors', $this->pesertaModel->errors());
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $peserta = $this->pesertaModel->find($id);
        if (!$peserta) {
            return redirect()->to(site_url('peserta'))->with('error', 'Peserta tidak ditemukan');
        }

        // Check if participant has quiz history
        $kuisModel = new \App\Models\KuisModel();
        $kuisCount = count($kuisModel->getKuisByPeserta($id));

        if ($kuisCount > 0) {
            return redirect()->to(site_url('peserta'))->with('error', "Peserta tidak dapat dihapus karena masih memiliki {$kuisCount} riwayat kuis");
        }

        if ($this->pesertaModel->delete($id)) {
            return redirect()->to(site_url('peserta'))->with('success', 'Peserta berhasil dihapus');
        }

        return redirect()->to(site_url('peserta'))->with('error', 'Gagal menghapus peserta');
    }

    public function resetToken($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $peserta = $this->pesertaModel->find($id);
        if (!$peserta) {
            return redirect()->to(site_url('peserta'))->with('error', 'Peserta tidak ditemukan');
        }

        $newToken = $this->pesertaModel->generateUniqueToken();

        if ($this->pesertaModel->update($id, ['token' => $newToken])) {
            return redirect()->to(site_url('peserta'))->with('success', 'Token berhasil direset: ' . $newToken);
        }

        return redirect()->to(site_url('peserta'))->with('error', 'Gagal mereset token');
    }

    public function showToken($id)
    {
        $this->requireAuth();
        $this->requireRole('ADMIN');

        $peserta = $this->pesertaModel->getPesertaWithToken($id);

        if (!$peserta) {
            return $this->jsonError('Peserta tidak ditemukan', 404);
        }

        return $this->jsonSuccess('Token berhasil diambil', [
            'token' => $peserta['token'],
            'peserta' => $peserta
        ]);
    }
}
