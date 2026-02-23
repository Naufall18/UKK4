# DOKUMENTASI LENGKAP PROJECT — PART 2
# Controller & Frontend (Views)

---

# BAB 6: BACKEND — CONTROLLER

## 6.1 Admin\DashboardController
**File:** `app/Http/Controllers/Admin/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'siswa')->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $pengembalianHariIni = Peminjaman::where('status', 'dikembalikan')
            ->whereDate('tgl_kembali_aktual', Carbon::today())
            ->count();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBuku', 'totalAnggota', 'peminjamanAktif',
            'pengembalianHariIni', 'peminjamanTerbaru'
        ));
    }
}
```

---

## 6.2 Admin\BukuController (CRUD Buku)
**File:** `app/Http/Controllers/Admin/BukuController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(10);
        return view('admin.buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('admin.buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create($request->all());

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update($request->all());

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
```

---

## 6.3 Admin\AnggotaController (CRUD Anggota)
**File:** `app/Http/Controllers/Admin/AnggotaController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('no_anggota', 'like', "%{$search}%");
            });
        }

        $anggotas = $query->orderBy('name')->paginate(10);
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Auto-generate nomor anggota
        $lastAnggota = User::where('role', 'siswa')
            ->orderBy('id', 'desc')
            ->first();
        $nextNum = $lastAnggota ? (intval(substr($lastAnggota->no_anggota, 4)) + 1) : 1;
        $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'no_anggota' => $noAnggota,
            'status_aktif' => true,
        ]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggotum)
    {
        return view('admin.anggota.edit', ['anggota' => $anggotum]);
    }

    public function update(Request $request, User $anggotum)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $anggotum->id,
            'email' => 'required|email|max:255|unique:users,email,' . $anggotum->id,
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'username', 'email', 'nis', 'kelas', 'no_hp', 'status_aktif']);

        // Password opsional — hanya update jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggotum->update($data);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
```

---

## 6.4 Admin\TransaksiController (Peminjaman & Pengembalian)
**File:** `app/Http/Controllers/Admin/TransaksiController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $today = Carbon::today();
        $denda = 0;

        // Hitung denda: Rp 1.000 per hari keterlambatan
        if ($today->gt($peminjaman->tgl_kembali_rencana)) {
            $hariTerlambat = $today->diffInDays($peminjaman->tgl_kembali_rencana);
            $denda = $hariTerlambat * 1000;
        }

        $peminjaman->update([
            'tgl_kembali_aktual' => $today,
            'status' => 'dikembalikan',
            'denda' => $denda,
        ]);

        // Kembalikan stok buku +1
        $peminjaman->buku->increment('stok');

        $message = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($denda, 0, ',', '.');
        }

        return back()->with('success', $message);
    }
}
```

---

## 6.5 Siswa\DashboardController
**File:** `app/Http/Controllers/Siswa/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bukuDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        $totalPeminjaman = Peminjaman::where('user_id', $userId)->count();

        $totalDenda = Peminjaman::where('user_id', $userId)
            ->sum('denda');

        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->orderBy('tgl_kembali_rencana')
            ->get();

        return view('siswa.dashboard', compact(
            'bukuDipinjam', 'totalPeminjaman', 'totalDenda', 'peminjamanAktif'
        ));
    }
}
```

---

## 6.6 Siswa\PinjamController (Pinjam Buku)
**File:** `app/Http/Controllers/Siswa/PinjamController.php`

```php
<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        // Hanya tampilkan buku yang stoknya > 0
        $query = Buku::where('stok', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(10);
        return view('siswa.pinjam', compact('bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $userId = Auth::id();
        $bukuId = $request->buku_id;

        // Cek: apakah siswa sudah pinjam buku ini dan belum dikembalikan?
        $sudahPinjam = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini. Kembalikan terlebih dahulu.');
        }

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        // Buat record peminjaman (batas 7 hari)
        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tgl_pinjam' => Carbon::today(),
            'tgl_kembali_rencana' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam. Batas pengembalian: ' . Carbon::today()->addDays(7)->format('d/m/Y'));
    }
}
```

---

## 6.7 Siswa\RiwayatController
**File:** `app/Http/Controllers/Siswa/RiwayatController.php`

```php
<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayats'));
    }
}
```

---

# BAB 7: BACKEND — ROUTING

**File:** `routes/web.php`

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PinjamController;
use App\Http\Controllers\Siswa\RiwayatController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes (hanya untuk guest / belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes — dilindungi middleware 'auth' + 'isAdmin'
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/buku', BukuController::class);
    Route::resource('/anggota', AnggotaController::class);
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});

// Siswa Routes — dilindungi middleware 'auth' + 'isSiswa'
Route::prefix('siswa')->middleware(['auth', 'isSiswa'])->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pinjam', [PinjamController::class, 'index'])->name('pinjam.index');
    Route::post('/pinjam', [PinjamController::class, 'store'])->name('pinjam.store');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});
```

---

# BAB 8: BACKEND — KONFIGURASI

## 8.1 AppServiceProvider (Pagination Bootstrap 5)
**File:** `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gunakan Bootstrap 5 untuk pagination (bukan Tailwind)
        Paginator::useBootstrapFive();
    }
}
```

---

Lanjutan frontend ada di file **DOKUMENTASI_PART3.md**
