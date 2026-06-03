<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PesertaSeeder extends Seeder
{
    public function run()
    {
        // Delete all participant data first
        $this->db->disableForeignKeyChecks();
        $this->db->table('peserta')->truncate();
        $this->db->enableForeignKeyChecks();

        $defaultPassword = password_hash('123456789', PASSWORD_DEFAULT);

        $data = [
            [
                'username'     => 'rismayanti',
                'nama_lengkap' => 'Rismayanti',
                'email'        => 'rismayanti04@gmail.com',
                'no_hp'        => '085889208250',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'elika',
                'nama_lengkap' => 'Elika',
                'email'        => 'elika.12@gmail.com',
                'no_hp'        => '085793840283',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'neviaindriyanti',
                'nama_lengkap' => 'Nevia Indriyanti',
                'email'        => 'Neviaindriyanti@gmail.com',
                'no_hp'        => '089572384615',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'nia',
                'nama_lengkap' => 'Nia',
                'email'        => 'Nia29@gmail.com',
                'no_hp'        => '087846274883',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'iin',
                'nama_lengkap' => 'Iin',
                'email'        => 'Iin01@gmail.com',
                'no_hp'        => '088836274788',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'lia',
                'nama_lengkap' => 'Lia',
                'email'        => 'Lia030112@gmail.com',
                'no_hp'        => '085876538172',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'faisalhida',
                'nama_lengkap' => 'Faisal Hida',
                'email'        => 'Faisal07@gmail.com',
                'no_hp'        => '085473281918',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'risma',
                'nama_lengkap' => 'Risma',
                'email'        => 'Risma.2010@gmail.com',
                'no_hp'        => '085877362915',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'nisaleviana',
                'nama_lengkap' => 'Nisa Leviana',
                'email'        => 'Nisa.Leviana@gmail.com',
                'no_hp'        => '085713872415',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'pysal',
                'nama_lengkap' => 'pysal',
                'email'        => 'Pysal@gmail.com',
                'no_hp'        => '081276369812',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'mira',
                'nama_lengkap' => 'Mira',
                'email'        => 'Mira27@gmail.com',
                'no_hp'        => '085834279461',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'cinta',
                'nama_lengkap' => 'Cinta',
                'email'        => 'Cinta.92@gmail.com',
                'no_hp'        => '085763518726',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'dinihairunnisa',
                'nama_lengkap' => 'Dini Hairunnisa',
                'email'        => 'Dini29@gmail.com',
                'no_hp'        => '085836153781',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'muhammadhasbullah',
                'nama_lengkap' => 'Muhammad Hasbullah',
                'email'        => 'Hasbullah15@gmail.com',
                'no_hp'        => '085852631836',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'alexsa',
                'nama_lengkap' => 'Alexsa',
                'email'        => 'Alexsa@gmail.com',
                'no_hp'        => '085877263761',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'riri',
                'nama_lengkap' => 'Riri',
                'email'        => 'Riri@gmail.com',
                'no_hp'        => '085763617357',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'anggacandranugraha',
                'nama_lengkap' => 'Angga Candra Nugraha',
                'email'        => 'Anggacandranugraha@gmail.com',
                'no_hp'        => '085763516812',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'fikrihairulsaleh',
                'nama_lengkap' => 'Fikri Hairul Saleh',
                'email'        => 'fikrihairul@gmail.com',
                'no_hp'        => '085776128376',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'andrisetiawan',
                'nama_lengkap' => 'Andri Setiawan',
                'email'        => 'andri.setiawan@gmail.com',
                'no_hp'        => '085895126361',
                'jenjang'      => 'HIGH_SCHOOL',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'muhammadrifki',
                'nama_lengkap' => 'Muhammad Rifki',
                'email'        => 'rifki@gmail.com',
                'no_hp'        => '085813287182',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'ahmadfauzi',
                'nama_lengkap' => 'Ahmad Fauzi',
                'email'        => 'ahmad.fauzi@gmail.com',
                'no_hp'        => '088276237163',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'saviraramadhani',
                'nama_lengkap' => 'Savira ramadhani',
                'email'        => 'savira.ramadhani@gmail.com',
                'no_hp'        => '085754535435',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'rizkialfarizhi',
                'nama_lengkap' => 'Rizki Alfarizhi',
                'email'        => 'rizki.al14@gmail.com',
                'no_hp'        => '089876428398',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'evanurvitasari',
                'nama_lengkap' => 'Eva nurvitasari',
                'email'        => 'evanur04@gmail.com',
                'no_hp'        => '085873249272',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'muhammadsahrul',
                'nama_lengkap' => 'muhammad sahrul',
                'email'        => 'sahrul23@gmail.com',
                'no_hp'        => '085789348278',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'revanpratama',
                'nama_lengkap' => 'revan pratama',
                'email'        => 'revan.pratama@gmail.com',
                'no_hp'        => '085864546127',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'fazrinurbakti',
                'nama_lengkap' => 'fazri nur bakti',
                'email'        => 'fazri.nur@gmail.com',
                'no_hp'        => '089874632987',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'dadanhamdani',
                'nama_lengkap' => 'dadan hamdani',
                'email'        => 'hamdani@gmail.com',
                'no_hp'        => '085746573283',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'jakasukmana',
                'nama_lengkap' => 'jaka sukmana',
                'email'        => 'jaka12@gmail.com',
                'no_hp'        => '085873643289',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'salsabila',
                'nama_lengkap' => 'salsabila',
                'email'        => 'salsabila@gmail.com',
                'no_hp'        => '085874628746',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'sarahkhaerunnisa',
                'nama_lengkap' => 'sarah khaerunnisa',
                'email'        => 'sarah@gmail.com',
                'no_hp'        => '085748297332',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'indahpuspita',
                'nama_lengkap' => 'indah puspita',
                'email'        => 'indah09@gmail.com',
                'no_hp'        => '081298327429',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'indridwirinjani',
                'nama_lengkap' => 'indri dwi rinjani',
                'email'        => 'indri.dwi.05@gmail.com',
                'no_hp'        => '085823429847',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'ayusalsabila',
                'nama_lengkap' => 'ayu salsabila',
                'email'        => 'ayu.salsabila21@gmail.com',
                'no_hp'        => '089583723263',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'muhammadrafa',
                'nama_lengkap' => 'muhammad rafa',
                'email'        => 'rafa08@gmail.com',
                'no_hp'        => '085877438178',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'muhammadfathan',
                'nama_lengkap' => 'muhammad fathan',
                'email'        => 'fathanm07@gmail.com',
                'no_hp'        => '089583743827',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'selina',
                'nama_lengkap' => 'selina',
                'email'        => 'selina290211@gmail.com',
                'no_hp'        => '085728427090',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'ahmadmuhaimin',
                'nama_lengkap' => 'ahmad muhaimin',
                'email'        => 'muhaimin.12@gmail.com',
                'no_hp'        => '085892374982',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'selviyanti',
                'nama_lengkap' => 'selviyanti',
                'email'        => 'selviyanti.07@gmail.com',
                'no_hp'        => '088873297137',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'rijalfauzi',
                'nama_lengkap' => 'Rijal Fauzi',
                'email'        => 'rijal.fauzi.10@gmail.com',
                'no_hp'        => '089831747183',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'khaerullatiframadhan',
                'nama_lengkap' => 'Khaerul latif ramadhan',
                'email'        => 'khaerul.latih.r.12@gmail.com',
                'no_hp'        => '085891274223',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'ridwanhasbi',
                'nama_lengkap' => 'Ridwan Hasbi',
                'email'        => 'ridwan.hasbi28@gmail.com',
                'no_hp'        => '081284374177',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'agussaputra',
                'nama_lengkap' => 'Agus saputra',
                'email'        => 'agussaputra07@gmail.com',
                'no_hp'        => '085892187321',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'kalisa',
                'nama_lengkap' => 'Kalisa',
                'email'        => 'kalisa08@gmail.com',
                'no_hp'        => '087823984721',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'naura',
                'nama_lengkap' => 'Naura',
                'email'        => 'naura123@gmail.com',
                'no_hp'        => '089573819373',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'yayan',
                'nama_lengkap' => 'Yayan',
                'email'        => 'yayan09@gmail.com',
                'no_hp'        => '089576351441',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
            [
                'username'     => 'nazila',
                'nama_lengkap' => 'Nazila',
                'email'        => 'nazila29@gmail.com',
                'no_hp'        => '081273721639',
                'jenjang'      => 'ELEMENTARY',
                'status'       => 'AKTIF',
            ],
        ];

        $insertCount = 0;
        foreach ($data as $peserta) {
            $peserta['password']     = $defaultPassword;
            $peserta['token']        = $this->generateToken();
            $peserta['waktu_dibuat'] = date('Y-m-d H:i:s');
            
            $this->db->table('peserta')->insert($peserta);
            $insertCount++;
        }

        echo "✅ PesertaSeeder completed! {$insertCount} participants created.\n";
        echo "   Password for all participants: 123456789\n";
    }

    /**
     * Generate a unique API token
     */
    private function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}
