<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        // Require authentication
        $this->requireAuth();

        // Set page title
        $this->data['page_title'] = 'Dashboard';

        // Get user statistics based on role
        $this->data['userStats'] = $this->getUserStats();

        return view('dashboard/index', $this->data);
    }

    /**
     * Get user statistics based on role
     */
    private function getUserStats()
    {
        $stats = [
            'total_users'   => 0,
            'total_soal'    => 0,
            'total_level'   => 0,
            'total_peserta' => 0,
            'total_kuis'    => 0,
        ];

        if ($this->currentUser['hak_akses'] === 'ADMIN') {
            // Admin can see all statistics
            $penggunaModel = new \App\Models\PenggunaModel();
            $stats['total_users'] = count($penggunaModel->getActiveUsers());

            $soalModel = new \App\Models\SoalModel();
            $stats['total_soal'] = count($soalModel->getActiveSoal());

            $levelModel = new \App\Models\LevelModel();
            $stats['total_level'] = count($levelModel->getAllOrdered());

            $pesertaModel = new \App\Models\PesertaModel();
            $stats['total_peserta'] = count($pesertaModel->getActivePeserta());

            $kuisModel = new \App\Models\KuisModel();
            $stats['total_kuis'] = count($kuisModel->getCompletedKuis());
        }

        return $stats;
    }
}
