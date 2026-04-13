<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $judul
 * @property string $pengarang
 * @property string $penerbit
 * @property string $tahun
 * @property string $kategori
 * @property string|null $deskripsi
 * @property int $stok
 * @property string|null $lokasi_rak
 * @property string|null $cover
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peminjaman> $peminjaman
 * @property-read int|null $peminjaman_count
 * @method static \Illuminate\Database\Eloquent\Builder|Buku newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buku newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buku query()
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereLokasiRak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku wherePenerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku wherePengarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buku whereUpdatedAt($value)
 */
	class Buku extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $kode_transaksi
 * @property int $user_id
 * @property int $buku_id
 * @property int $jumlah
 * @property int $durasi_hari
 * @property \Illuminate\Support\Carbon|null $tgl_pinjam
 * @property \Illuminate\Support\Carbon|null $tgl_kembali_rencana
 * @property \Illuminate\Support\Carbon|null $tgl_kembali_aktual
 * @property string $status
 * @property string $status_approval
 * @property int $denda
 * @property string $status_bayar_denda
 * @property string $kondisi_buku
 * @property string|null $catatan_kondisi
 * @property int $denda_kerusakan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Buku $buku
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman query()
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereBukuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereCatatanKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereDenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereDendaKerusakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereDurasiHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereKodeTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereKondisiBuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereStatusApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereStatusBayarDenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereTglKembaliAktual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereTglKembaliRencana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereTglPinjam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peminjaman whereUserId($value)
 */
	class Peminjaman extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengaturan whereValue($value)
 */
	class Pengaturan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $nis
 * @property string $kelas
 * @property string $no_hp
 * @property string $no_anggota
 * @property bool $status_aktif
 * @property string|null $foto_profil
 * @property-read string|null $foto_profil_url
 * @property string|null $support_request
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peminjaman> $peminjaman
 * @property-read int|null $peminjaman_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFotoProfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNoAnggota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSupportRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

