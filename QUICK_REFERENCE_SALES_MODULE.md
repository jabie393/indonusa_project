# QUICK REFERENCE - MODUL SALES

## 🚀 Mulai Cepat

### Akses Menu Sales Module
1. Login sebagai user dengan role "Sales"
2. Di sidebar → "Sales Module" (dropdown)
   - **Request Order (Penawaran)** - Kelola penawaran awal
   - **Sales Order (Pesanan)** - Kelola pesanan final

---

## 📋 Request Order (Penawaran)

### Buat Request Order Baru
```
Dashboard → Sales Module → Request Order (Penawaran) → [+ Request Order Baru]
```

**Form Fields:**
- **Nama Customer** ⭐ (required)
- **ID Customer** (optional)
- **Tanggal Kebutuhan** (optional, date picker)
- **Catatan** (optional, textarea)
- **Detail Barang:**
  - Pilih barang (dropdown, required)
  - Jumlah (positive integer, required)
  - Harga satuan (number, optional)
  - Subtotal (auto-calculated)

**Actions:**
- ✅ Buat Request Order
- ✅ Edit (jika status: pending)
- ✅ Lihat detail
- ✅ Konversi ke Sales Order (jika status: approved)

---

## 💼 Sales Order (Pesanan)

### Status Pesanan
```
pending (baru)
    ↓
in_process (dalam proses)
    ↓
shipped (dikirim)
    ↓
completed (selesai)
    
Atau bisa → cancelled (dibatalkan) dari status apapun
```

### Update Status
```
Detail Sales Order → [Update Status] → Pilih status baru → Simpan
```

### Update Pengiriman Per Item
```
Detail Sales Order → Lihat table items → Input "terkirim" qty → Update
```

**Auto Features:**
- ✅ Item status otomatis update (pending → partial → completed)
- ✅ Order auto-complete ketika semua items done
- ✅ Progress bar update real-time
- ✅ Timeline history otomatis tercatat

### Batalkan Pesanan
```
Detail Sales Order → [Batalkan Pesanan] → Isi alasan → Konfirmasi
```

---

## 📊 Status Tracking

### Request Order Status
| Status | Arti | Aksi |
|--------|------|------|
| pending | Menunggu approval | Edit, Lihat Detail |
| approved | Sudah disetujui | Konversi ke Sales Order |
| rejected | Ditolak | Lihat alasan |
| converted | Sudah menjadi Sales Order | View Sales Order |

### Sales Order Status
| Status | Arti | Aksi |
|--------|------|------|
| pending | Pesanan baru | Update status |
| in_process | Sedang diproses | Update status, tracking |
| shipped | Sudah dikirim | Update delivered qty |
| completed | Selesai | View only |
| cancelled | Dibatalkan | View only |

---

## 🔧 Database Relationships

```
Request Order (1) ──────── (Many) Request Order Items
   ├─ Sales User
   ├─ Approved By User
   └─ (1) Sales Order (hasil konversi)

Sales Order (1) ──────────── (Many) Sales Order Items
   ├─ Request Order
   ├─ Sales User
   ├─ Supervisor User (optional)
   ├─ Warehouse User (optional)
   └─ Approved By User (optional)

Request Order Item ─── Barang
Sales Order Item ──── Barang
```

---

## 📱 Responsive Features

✅ Mobile-friendly table
✅ Dropdown menu responsif
✅ Modal dialogs untuk aksi
✅ Touch-friendly buttons
✅ Bootstrap 5 grid system

---

## 🛡️ Security Notes

- ✅ Hanya owner yang bisa edit/view personal orders
- ✅ Role-based access control (Sales only)
- ✅ Server-side validation
- ✅ CSRF protection
- ✅ Transaction-safe operations

---

## 📞 Helpful Routes

| Fitur | Route | Method |
|-------|-------|--------|
| List Request Order | `/request-order` | GET |
| Buat Request Order | `/request-order/create` | GET |
| Simpan Request Order | `/request-order` | POST |
| Lihat Detail | `/request-order/{id}` | GET |
| Edit Request Order | `/request-order/{id}/edit` | GET |
| Update Request Order | `/request-order/{id}` | PUT |
| Konversi ke SO | `/request-order/{id}/convert` | POST |
| List Sales Order | `/sales-order` | GET |
| Lihat Detail SO | `/sales-order/{id}` | GET |
| Update SO Status | `/sales-order/{id}/status` | PUT |
| Update Delivered | `/sales-order-item/{id}/delivered` | PUT |
| Batalkan SO | `/sales-order/{id}/cancel` | POST |

---

## 🎯 Key Validations

✅ Customer name required
✅ Minimal 1 item per order
✅ Quantity harus positif
✅ Barang harus ada di database
✅ Delivered qty ≤ ordered qty
✅ Pembatalan minimal 10 karakter alasan

---

## 💡 Tips & Tricks

1. **Harga otomatis calculate**
   - Input qty dan harga, subtotal otomatis terisi
   - Total otomatis sum semua subtotal

2. **Konversi hanya bisa 1x**
   - Request Order tidak bisa di-convert 2x
   - Check di detail apakah sudah ada Sales Order

3. **Edit hanya saat pending**
   - Request Order hanya bisa edit jika status pending
   - Jika sudah approved/rejected, read-only

4. **Auto-completion tracking**
   - Ketika semua items "delivered 100%", SO otomatis "completed"
   - Tidak perlu manual update status

5. **Progress bar visual**
   - Lihat progress pengiriman dalam % di sidebar
   - Update real-time ketika update delivered qty

---

## 🔄 Common Workflows

### Scenario 1: Create & Convert
```
1. Create Request Order (Penawaran)
2. Customer sees penawaran → approve
3. Konversi ke Sales Order (Pesanan)
4. Track & deliver items
5. Complete order
```

### Scenario 2: Multiple Items
```
1. Create Request Order dengan 5 items
2. Deliver 3 items dulu
3. Items 3 → "completed", items 2 → "partial"
4. SO masih "in_process"
5. Deliver sisa 2 items
6. SO auto "completed" ketika 5/5 done
```

### Scenario 3: Cancel Order
```
1. Open Sales Order
2. Click [Batalkan Pesanan]
3. Input reason (min 10 char)
4. Confirm
5. Status → "cancelled"
6. View-only mode
```

---

## 📞 Troubleshooting

**Q: Tidak bisa konversi ke Sales Order**
A: Request Order harus status "approved" dulu

**Q: Delivered qty error**
A: Jangan input lebih dari quantity yang dipesan

**Q: Tidak bisa edit Request Order**
A: Hanya status "pending" yang bisa di-edit

**Q: Sidebar menu tidak muncul**
A: Login pakai user dengan role "Sales"

---

## 📚 Dokumentasi Lengkap

- Full docs: `SALES_MODULE_DOCUMENTATION.md`
- Implementation: `IMPLEMENTATION_SUMMARY.md`
- This file: `QUICK_REFERENCE.md` (you are here)

---

## ✨ Features at a Glance

```
Request Order Module:
├─ CRUD Operations
├─ Multi-item support
├─ Auto-calculation (subtotal/total)
├─ Status tracking (4 states)
├─ Edit capability (pending only)
└─ 1-click conversion to Sales Order

Sales Order Module:
├─ Auto-create from Request Order
├─ Per-item delivery tracking
├─ 5 status states
├─ Progress visualization
├─ Timeline history
├─ Batch cancellation
└─ Real-time status updates
```

---

**Version:** 1.0
**Date:** 13 November 2025
**Status:** ✅ Production Ready
