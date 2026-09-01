<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServisaCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $layanan = [
            [1, 'Servis Ringan Motor', 'Pemeriksaan kondisi mesin, rem, busi, kelistrikan, ban dan komponen umum motor', 'servis', 'motor', 60000],
            [2, 'Servis CVT Motor', 'Pembersihan dan pemeriksaan sistem CVT pada motor matic', 'servis', 'motor', 75000],
            [3, 'Ganti Oli Motor', 'Jasa penggantian oli motor, belum termasuk harga oli', 'servis', 'motor', 15000],
            [4, 'Servis Rem Motor', 'Pemeriksaan, pembersihan dan penyetelan sistem rem motor', 'perbaikan', 'motor', 40000],
            [5, 'Tambal Ban Motor', 'Perbaikan ban motor yang bocor', 'perbaikan', 'motor', 20000],
            [6, 'Pemeriksaan Mesin Motor', 'Pemeriksaan awal untuk mengetahui masalah pada mesin motor', 'servis', 'motor', 50000],
            [7, 'Pemeriksaan Kelistrikan Motor', 'Pemeriksaan aki, lampu, starter dan sistem kelistrikan motor', 'servis', 'motor', 40000],
            [8, 'Servis Ringan Mobil', 'Pemeriksaan rutin mesin, rem, cairan, kelistrikan dan kondisi umum mobil', 'servis', 'mobil', 200000],
            [9, 'Tune Up Mobil', 'Pemeriksaan dan penyetelan mesin untuk menjaga performa kendaraan', 'servis', 'mobil', 300000],
            [10, 'Ganti Oli Mobil', 'Jasa penggantian oli mobil, belum termasuk harga oli dan filter', 'servis', 'mobil', 50000],
            [11, 'Servis Rem Mobil', 'Pemeriksaan dan perawatan sistem pengereman mobil', 'perbaikan', 'mobil', 100000],
            [12, 'Tambal Ban Mobil', 'Perbaikan ban mobil yang bocor', 'perbaikan', 'mobil', 30000],
            [13, 'Pemeriksaan Mesin Mobil', 'Diagnosa awal untuk mengetahui masalah pada mesin mobil', 'servis', 'mobil', 100000],
            [14, 'Pemeriksaan Kelistrikan Mobil', 'Pemeriksaan aki, starter, lampu dan sistem kelistrikan mobil', 'servis', 'mobil', 75000],
        ];

        DB::table('layanan')->upsert(array_map(fn (array $item): array => [
            'id_layanan' => $item[0], 'nama_layanan' => $item[1], 'deskripsi' => $item[2],
            'kategori' => $item[3], 'jenis_kendaraan' => $item[4], 'harga' => $item[5],
            'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now,
        ], $layanan), ['id_layanan'], ['nama_layanan', 'deskripsi', 'kategori', 'jenis_kendaraan', 'harga', 'status', 'updated_at']);

        $spareparts = [
            [1, 'Oli Mesin Motor', 'motor', 55000, 20], [2, 'Oli Gardan Motor Matic', 'motor', 15000, 20],
            [3, 'Busi Motor', 'motor', 30000, 20], [4, 'Kampas Rem Motor', 'motor', 85000, 15],
            [5, 'Filter Udara Motor', 'motor', 65000, 15], [6, 'V-Belt Motor Matic', 'motor', 130000, 10],
            [7, 'Aki Motor', 'motor', 300000, 10], [8, 'Ban Motor', 'motor', 300000, 10],
            [9, 'Oli Mesin Mobil 1 Liter', 'mobil', 90000, 30], [10, 'Filter Oli Mobil', 'mobil', 50000, 20],
            [11, 'Busi Mobil', 'mobil', 50000, 20], [12, 'Kampas Rem Mobil', 'mobil', 300000, 10],
            [13, 'Filter Udara Mobil', 'mobil', 150000, 15], [14, 'Aki Mobil', 'mobil', 850000, 8],
            [15, 'Ban Mobil', 'mobil', 850000, 8],
        ];

        DB::table('sparepart')->upsert(array_map(fn (array $item): array => [
            'id_sparepart' => $item[0], 'nama_sparepart' => $item[1], 'jenis_kendaraan' => $item[2],
            'harga' => $item[3], 'stok' => $item[4], 'status' => 'aktif',
            'created_at' => $now, 'updated_at' => $now,
        ], $spareparts), ['id_sparepart'], ['nama_sparepart', 'jenis_kendaraan', 'harga', 'stok', 'status', 'updated_at']);
    }
}
