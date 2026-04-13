<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->enum('kondisi_buku', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik')->after('denda');
            $table->text('catatan_kondisi')->nullable()->after('kondisi_buku');
            $table->integer('denda_kerusakan')->default(0)->after('catatan_kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropColumn(['kondisi_buku', 'catatan_kondisi', 'denda_kerusakan']);
        });
    }
};
