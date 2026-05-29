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
            'total_users'        => 0,
            'total_soal'         => 0,
            'total_level'        => 0,
            'total_peserta'      => 0,
            'total_kuis'         => 0,
            'soal_elementary'    => 0,
            'soal_high_school'   => 0,
            'kuis_elementary'    => 0,
            'kuis_high_school'   => 0,
            'kuis_beginner'      => 0,
            'kuis_intermediate'  => 0,
            'kuis_advanced'      => 0,
        ];

        $soalModel = new \App\Models\SoalModel();
        $levelModel = new \App\Models\LevelModel();
        $pesertaModel = new \App\Models\PesertaModel();
        $kuisModel = new \App\Models\KuisModel();

        if ($this->currentUser['hak_akses'] === 'ADMIN') {
            // Admin can see all statistics
            $penggunaModel = new \App\Models\PenggunaModel();
            $stats['total_users'] = count($penggunaModel->getActiveUsers());
            $stats['total_soal'] = count($soalModel->getActiveSoal());
            $stats['soal_elementary'] = count($soalModel->getActiveSoalByJenjang('ELEMENTARY'));
            $stats['soal_high_school'] = count($soalModel->getActiveSoalByJenjang('HIGH_SCHOOL'));

            $stats['total_level'] = count($levelModel->getAllOrdered());
            $stats['total_peserta'] = count($pesertaModel->getActivePeserta());
            
            $allCompletedKuis = $kuisModel->getCompletedKuis();
            $stats['total_kuis'] = count($allCompletedKuis);
            foreach ($allCompletedKuis as $k) {
                if ($k['jenjang'] === 'ELEMENTARY') {
                    $stats['kuis_elementary']++;
                } else if ($k['jenjang'] === 'HIGH_SCHOOL') {
                    $stats['kuis_high_school']++;
                }
                
                if (($k['level_ditetapkan'] ?? '') === 'BEGINNER') {
                    $stats['kuis_beginner']++;
                } else if (($k['level_ditetapkan'] ?? '') === 'INTERMEDIATE') {
                    $stats['kuis_intermediate']++;
                } else if (($k['level_ditetapkan'] ?? '') === 'ADVANCED') {
                    $stats['kuis_advanced']++;
                }
            }
        } else {
            // INSTRUKTUR can see statistics for their own jenjang
            $jenjang = $this->currentUser['jenjang'] ?? '';
            
            $stats['total_soal'] = count($soalModel->getActiveSoalByJenjang($jenjang));
            if ($jenjang === 'ELEMENTARY') {
                $stats['soal_elementary'] = $stats['total_soal'];
            } else if ($jenjang === 'HIGH_SCHOOL') {
                $stats['soal_high_school'] = $stats['total_soal'];
            }

            $stats['total_level'] = count($levelModel->getAllOrdered());
            $stats['total_peserta'] = count($pesertaModel->where('status', 'AKTIF')->where('jenjang', $jenjang)->findAll());
            
            $allCompletedKuis = $kuisModel->getCompletedKuis($jenjang);
            $stats['total_kuis'] = count($allCompletedKuis);
            foreach ($allCompletedKuis as $k) {
                if (($k['level_ditetapkan'] ?? '') === 'BEGINNER') {
                    $stats['kuis_beginner']++;
                } else if (($k['level_ditetapkan'] ?? '') === 'INTERMEDIATE') {
                    $stats['kuis_intermediate']++;
                } else if (($k['level_ditetapkan'] ?? '') === 'ADVANCED') {
                    $stats['kuis_advanced']++;
                }
            }
        }

        return $stats;
    }
}
