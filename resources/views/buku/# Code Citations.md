# Code Citations

## License: unknown
https://github.com/ahsan-62/Admin-Dashboard/blob/d5dfaba257250f37d70a09b9e8e533c42304ea25/resources/views/Admin/layouts/pages/role/edit.blade.php

```
## Analisis Error

Error **"Gagal menghapus buku: No query results for model [App\Models\Buku] bulk-delete"** terjadi karena:

1. **Route sudah terdaftar** (`Route::post('/buku/bulk-delete', ...)` ada di [routes/web.php](routes/web.php#L28))
2. **Method `bulkDelete()` TIDAK ada** di [BukuController.php](app/Http/Controllers/BukuController.php)
3. **Form/checkbox untuk bulk delete TIDAK ada** di view [buku/index.blade.php](resources/views/buku/index.blade.php)

Sistem mencoba memanggil method yang tidak ada, sehingga error muncul.

---

## Langkah-Langkah Mengerjakan Tugas Bulk Delete

### **1. Tambah Checkbox di View (index.blade.php)**
Tambahkan di setiap card item dan select-all checkbox di awal tabel/card list.

### **2. Tambah Form untuk Bulk Delete**
Buat form dengan method POST yang mengirim ke route `buku.bulk-delete`.

### **3. Tambah JavaScript Select-All Logic**
Implementasikan event listener untuk checkbox select-all.

### **4. Buat Method `bulkDelete()` di Controller**
Implement method yang menerima array ID, melakukan delete, dan redirect dengan pesan sukses.

---

## Outline Code Program

### **A. Struktur Form di View (resources/views/buku/index.blade.php)**
```blade
<!-- Di atas card list, tambahkan form container -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Buku</h5>
        <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" disabled>
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Select All checkbox -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="select-all">
            <label class="form-check-label" for="select-all">
                Pilih Semua
            </label>
        </div>
    </div>
</div>
```


## License: unknown
https://github.com/ahsan-62/Admin-Dashboard/blob/d5dfaba257250f37d70a09b9e8e533c42304ea25/resources/views/Admin/layouts/pages/role/edit.blade.php

```
## Analisis Error

Error **"Gagal menghapus buku: No query results for model [App\Models\Buku] bulk-delete"** terjadi karena:

1. **Route sudah terdaftar** (`Route::post('/buku/bulk-delete', ...)` ada di [routes/web.php](routes/web.php#L28))
2. **Method `bulkDelete()` TIDAK ada** di [BukuController.php](app/Http/Controllers/BukuController.php)
3. **Form/checkbox untuk bulk delete TIDAK ada** di view [buku/index.blade.php](resources/views/buku/index.blade.php)

Sistem mencoba memanggil method yang tidak ada, sehingga error muncul.

---

## Langkah-Langkah Mengerjakan Tugas Bulk Delete

### **1. Tambah Checkbox di View (index.blade.php)**
Tambahkan di setiap card item dan select-all checkbox di awal tabel/card list.

### **2. Tambah Form untuk Bulk Delete**
Buat form dengan method POST yang mengirim ke route `buku.bulk-delete`.

### **3. Tambah JavaScript Select-All Logic**
Implementasikan event listener untuk checkbox select-all.

### **4. Buat Method `bulkDelete()` di Controller**
Implement method yang menerima array ID, melakukan delete, dan redirect dengan pesan sukses.

---

## Outline Code Program

### **A. Struktur Form di View (resources/views/buku/index.blade.php)**
```blade
<!-- Di atas card list, tambahkan form container -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Buku</h5>
        <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" disabled>
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Select All checkbox -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="select-all">
            <label class="form-check-label" for="select-all">
                Pilih Semua
            </label>
        </div>
    </div>
</div>
```


## License: unknown
https://github.com/nuranoer/project_wak_luang/blob/7ec33c8e895993392c2d6484c4d5e034a4f328ef/resources/views/admin/produk/index.blade.php

```
## Analisis Error

Error **"Gagal menghapus buku: No query results for model [App\Models\Buku] bulk-delete"** terjadi karena:

1. **Route sudah terdaftar** (`Route::post('/buku/bulk-delete', ...)` ada di [routes/web.php](routes/web.php#L28))
2. **Method `bulkDelete()` TIDAK ada** di [BukuController.php](app/Http/Controllers/BukuController.php)
3. **Form/checkbox untuk bulk delete TIDAK ada** di view [buku/index.blade.php](resources/views/buku/index.blade.php)

Sistem mencoba memanggil method yang tidak ada, sehingga error muncul.

---

## Langkah-Langkah Mengerjakan Tugas Bulk Delete

### **1. Tambah Checkbox di View (index.blade.php)**
Tambahkan di setiap card item dan select-all checkbox di awal tabel/card list.

### **2. Tambah Form untuk Bulk Delete**
Buat form dengan method POST yang mengirim ke route `buku.bulk-delete`.

### **3. Tambah JavaScript Select-All Logic**
Implementasikan event listener untuk checkbox select-all.

### **4. Buat Method `bulkDelete()` di Controller**
Implement method yang menerima array ID, melakukan delete, dan redirect dengan pesan sukses.

---

## Outline Code Program

### **A. Struktur Form di View (resources/views/buku/index.blade.php)**
```blade
<!-- Di atas card list, tambahkan form container -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Buku</h5>
        <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" disabled>
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Select All checkbox -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="select-all">
            <label class="form-check-label" for="select-all">
                Pilih Semua
            </label>
        </div>
    </div>
</div>

<!-- Di dalam setiap card buku, tambahkan checkbox -->
<div class="col-md-1">
    <div class="form-check">
        <input class="form-check-input buku-checkbox" type="checkbox" 
               name="buku_ids[]" value="{{ $buku->id }}" 
               id="buku{{ $buku->id }}">
    </div>
</div>
```

### **B. Method di Controller (app/Http/Controllers/BukuController.php)**
```php
public function bulkDelete(Request $request)
{
    try {
        $ids = $request->buku_ids;
        
        // Validasi input
        if (!$ids || empty($ids)) {
            return redirect()->route('buku.index')
                ->with('error', 'Pilih minimal 1 buku untuk dihapus!');
        }
        
        // Delete multiple records
        $deletedCount = Buku::whereIn('id', $ids)->delete();
        
        return redirect()->route('buku.index')
            ->with('success', $deletedCount . ' buku berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
    }
}
```

### **C. JavaScript untuk Select-All (di view atau script section)**
```javascript
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="buku_ids[]"]');
    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkDeleteBtn();
});

// Update tombol delete berdasarkan checkbox yang dicek
document.querySelectorAll('input[name="buku_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkDeleteBtn);
});

function updateBulkDeleteBtn() {
    const checked = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
    const btn = document.getElementById('bulk-delete-btn');
    btn.disabled = checked === 0;
    btn.textContent = checked > 0 ? `Hapus ${checked} Buku` : 'Hapus Terpilih';
}

// Handle bulk delete button click
document.getElementById('bulk-delete-btn').addEventListener('click', function(e) {
    e.preventDefault();
    const checkedCount = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus ${checkedCount} buku?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Copy selected checkboxes ke form dan submit
            const form = document.
```


## License: unknown
https://github.com/nuranoer/project_wak_luang/blob/7ec33c8e895993392c2d6484c4d5e034a4f328ef/resources/views/admin/produk/index.blade.php

```
## Analisis Error

Error **"Gagal menghapus buku: No query results for model [App\Models\Buku] bulk-delete"** terjadi karena:

1. **Route sudah terdaftar** (`Route::post('/buku/bulk-delete', ...)` ada di [routes/web.php](routes/web.php#L28))
2. **Method `bulkDelete()` TIDAK ada** di [BukuController.php](app/Http/Controllers/BukuController.php)
3. **Form/checkbox untuk bulk delete TIDAK ada** di view [buku/index.blade.php](resources/views/buku/index.blade.php)

Sistem mencoba memanggil method yang tidak ada, sehingga error muncul.

---

## Langkah-Langkah Mengerjakan Tugas Bulk Delete

### **1. Tambah Checkbox di View (index.blade.php)**
Tambahkan di setiap card item dan select-all checkbox di awal tabel/card list.

### **2. Tambah Form untuk Bulk Delete**
Buat form dengan method POST yang mengirim ke route `buku.bulk-delete`.

### **3. Tambah JavaScript Select-All Logic**
Implementasikan event listener untuk checkbox select-all.

### **4. Buat Method `bulkDelete()` di Controller**
Implement method yang menerima array ID, melakukan delete, dan redirect dengan pesan sukses.

---

## Outline Code Program

### **A. Struktur Form di View (resources/views/buku/index.blade.php)**
```blade
<!-- Di atas card list, tambahkan form container -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Buku</h5>
        <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" disabled>
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Select All checkbox -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="select-all">
            <label class="form-check-label" for="select-all">
                Pilih Semua
            </label>
        </div>
    </div>
</div>

<!-- Di dalam setiap card buku, tambahkan checkbox -->
<div class="col-md-1">
    <div class="form-check">
        <input class="form-check-input buku-checkbox" type="checkbox" 
               name="buku_ids[]" value="{{ $buku->id }}" 
               id="buku{{ $buku->id }}">
    </div>
</div>
```

### **B. Method di Controller (app/Http/Controllers/BukuController.php)**
```php
public function bulkDelete(Request $request)
{
    try {
        $ids = $request->buku_ids;
        
        // Validasi input
        if (!$ids || empty($ids)) {
            return redirect()->route('buku.index')
                ->with('error', 'Pilih minimal 1 buku untuk dihapus!');
        }
        
        // Delete multiple records
        $deletedCount = Buku::whereIn('id', $ids)->delete();
        
        return redirect()->route('buku.index')
            ->with('success', $deletedCount . ' buku berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
    }
}
```

### **C. JavaScript untuk Select-All (di view atau script section)**
```javascript
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="buku_ids[]"]');
    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkDeleteBtn();
});

// Update tombol delete berdasarkan checkbox yang dicek
document.querySelectorAll('input[name="buku_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkDeleteBtn);
});

function updateBulkDeleteBtn() {
    const checked = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
    const btn = document.getElementById('bulk-delete-btn');
    btn.disabled = checked === 0;
    btn.textContent = checked > 0 ? `Hapus ${checked} Buku` : 'Hapus Terpilih';
}

// Handle bulk delete button click
document.getElementById('bulk-delete-btn').addEventListener('click', function(e) {
    e.preventDefault();
    const checkedCount = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus ${checkedCount} buku?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Copy selected checkboxes ke form dan submit
            const form = document.
```

