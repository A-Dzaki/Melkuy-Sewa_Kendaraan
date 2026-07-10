<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KendaraanFactory extends Factory
{
    protected $model = Kendaraan::class;

    public function definition(): array
    {
        $jenis = fake()->randomElement(['motor', 'mobil']);

        if ($jenis === 'motor') {
            $merk = fake()->randomElement([
                'Honda',
                'Yamaha',
                'Suzuki',
                'Kawasaki',
            ]);

            $nama = match ($merk) {
                'Honda' => fake()->randomElement([
                    'Beat',
                    'Vario 160',
                    'PCX 160',
                    'CBR150R',
                ]),
                'Yamaha' => fake()->randomElement([
                    'NMAX',
                    'Aerox',
                    'Mio M3',
                    'R15',
                ]),
                'Suzuki' => fake()->randomElement([
                    'Satria F150',
                    'GSX-R150',
                    'Address',
                ]),
                'Kawasaki' => fake()->randomElement([
                    'Ninja 250',
                    'KLX 150',
                    'W175',
                ]),
            };

            $transmisi = fake()->randomElement([
                'Manual',
                'Matic',
            ]);

            $harga = fake()->numberBetween(80000, 250000);
        } else {

            $merk = fake()->randomElement([
                'Toyota',
                'Honda',
                'Daihatsu',
                'Mitsubishi',
                'Suzuki',
            ]);

            $nama = match ($merk) {
                'Toyota' => fake()->randomElement([
                    'calya',
                    'Innova',
                    'Raize',
                    'Agya',
                ]),
                'Honda' => fake()->randomElement([
                    'Brio',
                    'HR-V',
                    'CR-V',
                    'Mobilio',
                ]),
                'Daihatsu' => fake()->randomElement([
                    'Xenia',
                    'Sigra',
                    'Terios',
                ]),
                'Mitsubishi' => fake()->randomElement([
                    'Xpander',
                    'Pajero Sport',
                ]),
                'Suzuki' => fake()->randomElement([
                    'Ertiga',
                    'XL7',
                ]),
            };

            $transmisi = fake()->randomElement([
                'Manual',
                'Matic',
            ]);

            $harga = fake()->numberBetween(250000, 700000);
        }

        return [
            'kode_kendaraan' => Kendaraan::generateKode(),
            'jenis' => $jenis,
            'merk' => $merk,
            'nama' => $nama,
            'tahun' => fake()->numberBetween(2018, 2025),
            'warna' => fake()->randomElement([
                'Hitam',
                'Putih',
                'Merah',
                'Silver',
                'Abu-abu',
                'Biru',
            ]),
            'transmisi' => $transmisi,
            'harga_sewa' => $harga,
            'status' => fake()->randomElement([
                'tersedia',
            ]),
            'deskripsi' => fake()->paragraph(3),
        ];
    }
}