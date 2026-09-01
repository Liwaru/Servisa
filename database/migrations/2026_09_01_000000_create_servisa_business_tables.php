<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table): void {
            $table->id('id_layanan');
            $table->string('nama_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['servis', 'perbaikan'])->default('servis');
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'semua'])->default('semua');
            $table->decimal('harga', 12, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('sparepart', function (Blueprint $table): void {
            $table->id('id_sparepart');
            $table->string('nama_sparepart', 100);
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'semua'])->default('semua');
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('kendaraan', function (Blueprint $table): void {
            $table->id('id_kendaraan');
            $table->foreignId('id_pelanggan')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->string('merk', 50);
            $table->string('model', 50);
            $table->string('no_polisi', 15)->unique();
            $table->string('tahun', 4)->nullable();
            $table->string('warna', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('servis', function (Blueprint $table): void {
            $table->id('id_servis');
            $table->string('kode_servis', 30)->unique();
            $table->foreignId('id_pelanggan')->constrained('users', 'id_user');
            $table->foreignId('id_kendaraan')->constrained('kendaraan', 'id_kendaraan');
            $table->enum('jenis_servis', ['bengkel', 'panggilan']);
            $table->text('keluhan');
            $table->dateTime('tanggal_servis')->nullable();
            $table->text('alamat_lokasi')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('biaya_panggilan', 12, 2)->default(0);
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->enum('status_servis', ['menunggu', 'diterima', 'mekanik_ditugaskan', 'menuju_lokasi', 'tiba_di_lokasi', 'pemeriksaan', 'dalam_perbaikan', 'selesai_servis', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pembayaran', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar');
            $table->text('hasil_pemeriksaan')->nullable();
            $table->text('catatan_mekanik')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_servis', function (Blueprint $table): void {
            $table->id('id_detail');
            $table->foreignId('id_servis')->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->foreignId('id_layanan')->nullable()->constrained('layanan', 'id_layanan')->nullOnDelete();
            $table->foreignId('id_sparepart')->nullable()->constrained('sparepart', 'id_sparepart')->nullOnDelete();
            $table->string('keterangan', 150)->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('penugasan_mekanik', function (Blueprint $table): void {
            $table->id('id_penugasan');
            $table->foreignId('id_servis')->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->foreignId('id_mekanik')->constrained('users', 'id_user');
            $table->foreignId('id_admin')->constrained('users', 'id_user');
            $table->dateTime('tanggal_ditugaskan')->useCurrent();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->enum('status', ['ditugaskan', 'diterima', 'selesai'])->default('ditugaskan');
            $table->timestamps();
        });

        Schema::create('riwayat_status', function (Blueprint $table): void {
            $table->id('id_riwayat');
            $table->foreignId('id_servis')->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->nullOnDelete();
            $table->string('status', 50);
            $table->text('catatan')->nullable();
            $table->timestamp('waktu')->useCurrent();
        });

        Schema::create('pembayaran', function (Blueprint $table): void {
            $table->id('id_pembayaran');
            $table->foreignId('id_servis')->unique()->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->enum('metode', ['cash', 'qris', 'e_wallet']);
            $table->enum('payment_channel', ['cash', 'qris', 'gopay', 'dana', 'ovo', 'shopeepay'])->nullable();
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['menunggu', 'berhasil', 'gagal'])->default('menunggu');
            $table->foreignId('id_penerima')->nullable()->constrained('users', 'id_user')->nullOnDelete();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('riwayat_status');
        Schema::dropIfExists('penugasan_mekanik');
        Schema::dropIfExists('detail_servis');
        Schema::dropIfExists('servis');
        Schema::dropIfExists('kendaraan');
        Schema::dropIfExists('sparepart');
        Schema::dropIfExists('layanan');
    }
};
