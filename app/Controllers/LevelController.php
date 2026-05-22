<?php

namespace App\Controllers;

use App\Models\LevelModel;

class LevelController extends BaseController
{
    protected $levelModel;

    public function __construct()
    {
        $this->levelModel = new LevelModel();
    }

    public function index()
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $this->data['page_title'] = 'Daftar Level';
        $this->data['levels'] = $this->levelModel->getAllOrdered();

        return view('level/index', $this->data);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $this->data['page_title'] = 'Tambah Level Baru';

        return view('level/form', $this->data);
    }

    public function store()
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $nilai_min = $this->request->getPost('nilai_min');
        $nilai_max = $this->request->getPost('nilai_max');

        // Custom validation: nilai_max must be >= nilai_min
        if ($nilai_max < $nilai_min) {
            return redirect()->back()->with('error', 'Nilai maksimum harus lebih besar atau sama dengan nilai minimum')
                                       ->withInput();
        }

        $data = [
            'nama_level' => $this->request->getPost('nama_level'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'nilai_min'  => $nilai_min,
            'nilai_max'  => $nilai_max,
        ];

        if ($this->levelModel->insert($data)) {
            return redirect()->to(site_url('level'))->with('success', 'Level berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan level')
                                   ->with('errors', $this->levelModel->errors())
                                   ->withInput();
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $this->data['page_title'] = 'Edit Level';
        $this->data['level'] = $this->levelModel->find($id);

        if (!$this->data['level']) {
            return redirect()->to(site_url('level'))->with('error', 'Level tidak ditemukan');
        }

        return view('level/form', $this->data);
    }

    public function update($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $nilai_min = $this->request->getPost('nilai_min');
        $nilai_max = $this->request->getPost('nilai_max');

        // Custom validation: nilai_max must be >= nilai_min
        if ($nilai_max < $nilai_min) {
            return redirect()->back()->with('error', 'Nilai maksimum harus lebih besar atau sama dengan nilai minimum')
                                       ->withInput();
        }

        $data = [
            'nama_level' => $this->request->getPost('nama_level'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'nilai_min'  => $nilai_min,
            'nilai_max'  => $nilai_max,
        ];

        if ($this->levelModel->update($id, $data)) {
            return redirect()->to(site_url('level'))->with('success', 'Level berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui level')
                                   ->with('errors', $this->levelModel->errors())
                                   ->withInput();
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAnyRole(['ADMIN', 'INSTRUKTUR']);

        $level = $this->levelModel->find($id);
        if (!$level) {
            return redirect()->to(site_url('level'))->with('error', 'Level tidak ditemukan');
        }

        // Check if level has questions
        $soalModel = new \App\Models\SoalModel();
        $soalCount = $soalModel->countByLevel($id);

        if ($soalCount > 0) {
            return redirect()->to(site_url('level'))->with('error', "Level tidak dapat dihapus karena masih memiliki {$soalCount} soal");
        }

        if ($this->levelModel->delete($id)) {
            return redirect()->to(site_url('level'))->with('success', 'Level berhasil dihapus');
        }

        return redirect()->to(site_url('level'))->with('error', 'Gagal menghapus level');
    }
}
