<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialis = [
            [
                'name' => 'Farmakologi Klinik dan Anestesiologi Veteriner',
            ],
            [
                'name' => 'Parasitologi Klinik Veteriner',
            ],
            [
                'name' => 'Ilmu Bedah dan Bedah Plastik Hewan',
            ],
            [
                'name' => 'Ilmu Kesehatan Hewan Ternak Besar (Penyakit Dalam)',
            ],
            [
                'name' => 'Ilmu Kesehatan Hewan Ternak Kecil (Penyakit Dalam)',
            ],
            [
                'name' => 'Ilmu Manajemen Kesehatan Unggas',
            ],
            [
                'name' => 'Ilmu Kesehatan Akuakultur (Ikan)',
            ],
            [
                'name' => 'Obstetri dan Ginekologi Hewan (Theriogenologi)',
            ],
            [
                'name' => 'Ilmu Kesehatan Kepala dan Leher Hewan (Gigi, THT, Mata, Bedah Kepala dan Leher)',
            ],
            [
                'name' => 'Ilmu Penyakit Hewan Kesayangan Mamalia (Penyakit Dalam)',
            ],
            [
                'name' => 'Ilmu Penyakit Hewan Kesayangan Reptil dan Amphibi (Penyakit Dalam)',
            ],
            [
                'name' => 'Ilmu Penyakit Hewan Kesayangan Unggas (Penyakit Dalam)',
            ],
            [
                'name' => 'lmu Manajemen Kebun Binatang dan Kesehatan Satwa Liar',
            ],
            [
                'name' => 'Ilmu Emergensi Medis dan Perawatan Kritis Hewan',
            ],
            [
                'name' => 'Ilmu Kesehatan Kulit, Bulu, Rambut, dan Kelamin Hewan',
            ],
            [
                'name' => 'Radiologi, Alat-Alat Diagnostik, dan Tehnologi Veteriner',
            ],
            [
                'name' => 'Patologi Klinik Veteriner',
            ],
            [
                'name' => 'Patologi Anatomi Veteriner',
            ],
            [
                'name' => 'Ilmu Konservasi Medik Veteriner dan Medikolegal Veteriner',
            ],
            [
                'name' => 'Ilmu Komparasi Pakan dan Nutrisi Klinik Hewan',
            ],
            [
                'name' => 'Ilmu Perilaku Hewan',
            ],
            [
                'name' => 'Ilmu Genetik dan Pemuliaan Hewan',
            ],
            [
                'name' => 'Ilmu Bedah Orthopedi Hewan dan Rehabilitasi Medik Veteriner',
            ],
            [
                'name' => 'Epidemiologi Veteriner dan Kedokteran Hewan Pencegahan',
            ],
            [
                'name' => 'Ilmu Kesehatan Masyarakat Veteriner (Hiegine Pangan)',
            ],
            [
                'name' => 'Mikrobiologi Klinik Veteriner',
            ],
            [
                'name' => 'Ilmu Akupunktur dan Pengobatan Alternatif Hewan',
            ],
            [
                'name' => 'Ilmu Kesehatan Hewan Laboratorium',
            ],
            [
                'name' => 'Ilmu Kesehatan Anak Hewan dan Ilmu Bedah Anak Hewan (Pediatrik Veteriner)',
            ]
        ];

        foreach($specialis as $key => $value){
            Specialist::query()->create($value);
        }
    }
}
