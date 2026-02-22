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
            'total_users' => 0,
            'total_materi' => 0,
            'total_soal' => 0,
            'total_ujian' => 0,
        ];

        if ($this->currentUser['hak_akses'] === 'ADMIN') {
            // Admin can see all statistics
            $penggunaModel = new \App\Models\PenggunaModel();
            $stats['total_users'] = count($penggunaModel->getActiveUsers());
        }

        return $stats;
    }
}
