<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->integer('durasi_hari')->default(7)->after('buku_id');
            // Allow tgl_pinjam and tgl_kembali_rencana to be null initially since it's pending
            $table->date('tgl_pinjam')->nullable()->change();
            $table->date('tgl_kembali_rencana')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'durasi_hari']);
            $table->date('tgl_pinjam')->nullable(false)->change();
            $table->date('tgl_kembali_rencana')->nullable(false)->change();
        });
    }
};
