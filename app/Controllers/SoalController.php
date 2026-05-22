<?php

namespace App\Controllers;

use App\Models\SoalModel;
use App\Models\LevelModel;

class SoalController extends BaseController
{
    protected $soalModel;
    protected $levelModel;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->levelModel = new LevelModel();
    }

    public function index()
    {
        $this->requireAuth();

        $this->data['page_title'] = 'Daftar Soal';
        $this->data['levels'] = $this->levelModel->getAllOrdered();

        $id_level = $this->request->getGet('level');
        if ($id_level) {
            $this->data['soal'] = $this->soalModel->getAllWithLevel($id_level);
        } else {
            $this->data['soal'] = $this->soalModel->getAllWithLevel();
        }

        return view('soal/index', $this->data);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $this->data['page_title'] = 'Tambah Soal Baru';
        $this->data['levels'] = $this->levelModel->getAllOrdered();

        return view('soal/form', $this->data);
    }

    public function store()
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $data = [
            'pertanyaan'    => $this->request->getPost('pertanyaan'),
            'opsi_a'        => $this->request->getPost('opsi_a'),
            'opsi_b'        => $this->request->getPost('opsi_b'),
            'opsi_c'        => $this->request->getPost('opsi_c'),
            'opsi_d'        => $this->request->getPost('opsi_d'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar'),
            'id_level'      => $this->request->getPost('id_level'),
            'dibuat_oleh'   => $this->currentUser['id_pengguna'],
            'status'        => 'AKTIF',
        ];

        if ($this->soalModel->insert($data)) {
            return redirect()->to(site_url('soal'))->with('success', 'Soal berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan soal')
                                   ->with('errors', $this->soalModel->errors());
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $this->data['page_title'] = 'Edit Soal';
        $this->data['soal'] = $this->soalModel->getSoalWithLevel($id);
        $this->data['levels'] = $this->levelModel->getAllOrdered();

        if (!$this->data['soal']) {
            return redirect()->to(site_url('soal'))->with('error', 'Soal tidak ditemukan');
        }

        return view('soal/form', $this->data);
    }

    public function update($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $data = [
            'pertanyaan'    => $this->request->getPost('pertanyaan'),
            'opsi_a'        => $this->request->getPost('opsi_a'),
            'opsi_b'        => $this->request->getPost('opsi_b'),
            'opsi_c'        => $this->request->getPost('opsi_c'),
            'opsi_d'        => $this->request->getPost('opsi_d'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar'),
            'id_level'      => $this->request->getPost('id_level'),
        ];

        if ($this->soalModel->update($id, $data)) {
            return redirect()->to(site_url('soal'))->with('success', 'Soal berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui soal')
                                   ->with('errors', $this->soalModel->errors());
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $soal = $this->soalModel->find($id);
        if (!$soal) {
            return redirect()->to(site_url('soal'))->with('error', 'Soal tidak ditemukan');
        }

        if ($this->soalModel->delete($id)) {
            return redirect()->to(site_url('soal'))->with('success', 'Soal berhasil dihapus');
        }

        return redirect()->to(site_url('soal'))->with('error', 'Gagal menghapus soal');
    }

    public function toggleStatus($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $soal = $this->soalModel->find($id);
        if (!$soal) {
            return $this->jsonError('Soal tidak ditemukan', 404);
        }

        $newStatus = $soal['status'] === 'AKTIF' ? 'NONAKTIF' : 'AKTIF';

        if ($this->soalModel->update($id, ['status' => $newStatus])) {
            return $this->jsonSuccess('Status soal berhasil diperbarui', ['status' => $newStatus]);
        }

        return $this->jsonError('Gagal memperbarui status soal');
    }
}
