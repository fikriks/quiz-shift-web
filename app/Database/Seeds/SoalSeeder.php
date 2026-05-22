<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run()
    {
        // First, get the level IDs
        $levels = $this->db->table('level')->get()->getResultArray();
        $levelMap = [];
        foreach ($levels as $level) {
            $levelMap[$level['nama_level']] = $level['id_level'];
        }

        // Get admin user ID for dibuat_oleh field
        $admin = $this->db->table('pengguna')->where('nama_pengguna', 'admin123')->get()->getRowArray();
        $adminId = $admin ? $admin['id_pengguna'] : 1;

        $data = [
            // BEGINNER LEVEL QUESTIONS (0-59 points)
            // Simple Present Tense
            [
                'pertanyaan'   => 'She _____ to school every day.',
                'opsi_a'       => 'go',
                'opsi_b'       => 'goes',
                'opsi_c'       => 'going',
                'opsi_d'       => 'gone',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Articles
            [
                'pertanyaan'   => 'I saw _____ elephant at the zoo yesterday.',
                'opsi_a'       => 'a',
                'opsi_b'       => 'an',
                'opsi_c'       => 'the',
                'opsi_d'       => 'no article',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Prepositions
            [
                'pertanyaan'   => 'The book is _____ the table.',
                'opsi_a'       => 'in',
                'opsi_b'       => 'at',
                'opsi_c'       => 'on',
                'opsi_d'       => 'by',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Plural nouns
            [
                'pertanyaan'   => 'There are many _____ in the garden.',
                'opsi_a'       => 'flower',
                'opsi_b'       => 'flowers',
                'opsi_c'       => 'floweres',
                'opsi_d'       => 'flower\'s',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Subject-verb agreement
            [
                'pertanyaan'   => 'They _____ playing football now.',
                'opsi_a'       => 'is',
                'opsi_b'       => 'am',
                'opsi_c'       => 'are',
                'opsi_d'       => 'be',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Past simple
            [
                'pertanyaan'   => 'I _____ my homework last night.',
                'opsi_a'       => 'do',
                'opsi_b'       => 'did',
                'opsi_c'       => 'done',
                'opsi_d'       => 'doing',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Possessive adjectives
            [
                'pertanyaan'   => 'This is _____ book.',
                'opsi_a'       => 'he',
                'opsi_b'       => 'his',
                'opsi_c'       => 'him',
                'opsi_d'       => 'he\'s',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Present Continuous
            [
                'pertanyaan'   => 'Look! It _____ now.',
                'opsi_a'       => 'rains',
                'opsi_b'       => 'rain',
                'opsi_c'       => 'is raining',
                'opsi_d'       => 'rained',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['BEGINNER'] ?? 1,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],

            // INTERMEDIATE LEVEL QUESTIONS (60-79 points)
            // First Conditional
            [
                'pertanyaan'   => 'If it _____ tomorrow, we will cancel the picnic.',
                'opsi_a'       => 'rains',
                'opsi_b'       => 'will rain',
                'opsi_c'       => 'rained',
                'opsi_d'       => 'would rain',
                'jawaban_benar'=> 'A',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Present Perfect
            [
                'pertanyaan'   => 'I _____ to Paris three times.',
                'opsi_a'       => 'have gone',
                'opsi_b'       => 'has gone',
                'opsi_c'       => 'went',
                'opsi_d'       => 'have been',
                'jawaban_benar'=> 'D',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Modal verbs
            [
                'pertanyaan'   => 'You _____ smoke in public places.',
                'opsi_a'       => 'mustn\'t',
                'opsi_b'       => 'don\'t have to',
                'opsi_c'       => 'needn\'t',
                'opsi_d'       => 'shouldn\'t to',
                'jawaban_benar'=> 'A',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Passive voice
            [
                'pertanyaan'   => 'The letter _____ by Sarah yesterday.',
                'opsi_a'       => 'writes',
                'opsi_b'       => 'wrote',
                'opsi_c'       => 'was written',
                'opsi_d'       => 'is written',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Second Conditional
            [
                'pertanyaan'   => 'If I _____ rich, I would travel around the world.',
                'opsi_a'       => 'am',
                'opsi_b'       => 'be',
                'opsi_c'       => 'was',
                'opsi_d'       => 'were',
                'jawaban_benar'=> 'D',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Reported speech
            [
                'pertanyaan'   => 'She said she _____ busy that day.',
                'opsi_a'       => 'is',
                'opsi_b'       => 'was',
                'opsi_c'       => 'will be',
                'opsi_d'       => 'has been',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Gerund vs Infinitive
            [
                'pertanyaan'   => 'I enjoy _____ tennis on weekends.',
                'opsi_a'       => 'play',
                'opsi_b'       => 'to play',
                'opsi_c'       => 'playing',
                'opsi_d'       => 'played',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Relative clauses
            [
                'pertanyaan'   => 'The man _____ car was stolen called the police.',
                'opsi_a'       => 'who',
                'opsi_b'       => 'which',
                'opsi_c'       => 'whose',
                'opsi_d'       => 'that',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['INTERMEDIATE'] ?? 2,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],

            // ADVANCED LEVEL QUESTIONS (80-100 points)
            // Third Conditional
            [
                'pertanyaan'   => 'If I _____ known about the traffic, I would have left earlier.',
                'opsi_a'       => 'have',
                'opsi_b'       => 'had',
                'opsi_c'       => 'would have',
                'opsi_d'       => 'did',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Inversion
            [
                'pertanyaan'   => 'Seldom _____ such a beautiful sunset.',
                'opsi_a'       => 'I see',
                'opsi_b'       => 'I saw',
                'opsi_c'       => 'do I see',
                'opsi_d'       => 'have I seen',
                'jawaban_benar'=> 'D',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Subjunctive
            [
                'pertanyaan'   => 'It is essential that he _____ on time.',
                'opsi_a'       => 'arrives',
                'opsi_b'       => 'arrive',
                'opsi_c'       => 'will arrive',
                'opsi_d'       => 'arrived',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Mixed Conditional
            [
                'pertanyaan'   => 'If I hadn\'t eaten so much, I _____ sick now.',
                'opsi_a'       => 'wouldn\'t feel',
                'opsi_b'       => 'wouldn\'t have felt',
                'opsi_c'       => 'won\'t feel',
                'opsi_d'       => 'didn\'t feel',
                'jawaban_benar'=> 'A',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Causative "have"
            [
                'pertanyaan'   => 'I need to _____ my car repaired.',
                'opsi_a'       => 'make',
                'opsi_b'       => 'do',
                'opsi_c'       => 'have',
                'opsi_d'       => 'get',
                'jawaban_benar'=> 'C',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Perfect modals
            [
                'pertanyaan'   => 'She _____ have studied harder for the exam.',
                'opsi_a'       => 'should',
                'opsi_b'       => 'would',
                'opsi_c'       => 'must',
                'opsi_d'       => 'can',
                'jawaban_benar'=> 'A',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Future Perfect
            [
                'pertanyaan'   => 'By next year, I _____ my degree.',
                'opsi_a'       => 'will finish',
                'opsi_b'       => 'will have finished',
                'opsi_c'       => 'will be finishing',
                'opsi_d'       => 'have finished',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            // Participle clauses
            [
                'pertanyaan'   => '_____ by the noise, the bird flew away.',
                'opsi_a'       => 'Frightening',
                'opsi_b'       => 'Frightened',
                'opsi_c'       => 'To frighten',
                'opsi_d'       => 'Having frightened',
                'jawaban_benar'=> 'B',
                'id_level'     => $levelMap['ADVANCED'] ?? 3,
                'dibuat_oleh'  => $adminId,
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('soal')->insertBatch($data);

        echo "✅ SoalSeeder completed! " . count($data) . " questions created.\n";
    }
}
