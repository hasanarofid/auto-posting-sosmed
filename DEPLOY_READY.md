# ✅ Folder `deploy/` Siap untuk Upload!

## 🎉 Status: SIAP DEPLOY

Folder `deploy/` sudah berisi semua file yang diperlukan untuk deployment ke cPanel.

## 📦 Isi Folder Deploy

✅ **Frontend React** (build production)
- `index.html`
- `static/` (CSS, JS, images)
- `.htaccess` (routing)

✅ **Backend PHP**
- `api/index.php` (main entry)
- `api/config.php` (config loader)
- `api/config.local.php.example` (template)
- `api/functions.php` (helpers)
- `api/routes/` (semua API routes)
- `api/.htaccess` (API routing)
- `api/uploads/` (folder upload)
- `api/data/` (folder data)

✅ **Dokumentasi**
- `README.md` (dokumentasi utama)
- `UPLOAD_INSTRUCTIONS.md` (panduan upload)
- `CHECKLIST.txt` (checklist)
- `README_DEPLOY.txt` (quick reference)

## 🚀 Langkah Selanjutnya

### 1. Upload via FileZilla

1. Buka **FileZilla**
2. Connect ke server cPanel
3. Navigate ke: `public_html/sosmed/`
4. Upload **semua file dan folder** dari `deploy/`

### 2. Setup di cPanel

1. Buat `config.local.php` di `api/`
2. Isi credentials API
3. Set permissions folder `uploads/` dan `data/` → **755**

### 3. Update OAuth Redirect URIs

Update di semua platform:
- Facebook: `https://sosmed.solusicodekata.com/api/facebook/callback`
- LinkedIn: `https://sosmed.solusicodekata.com/api/linkedin/callback`
- TikTok: `https://sosmed.solusicodekata.com/api/tiktok/callback`

### 4. Test

- Frontend: https://sosmed.solusicodekata.com/
- API: https://sosmed.solusicodekata.com/api/health

## 📚 Baca Dokumentasi

Lihat file di folder `deploy/`:
- **UPLOAD_INSTRUCTIONS.md** - Panduan lengkap upload via FileZilla
- **CHECKLIST.txt** - Checklist deployment
- **README.md** - Dokumentasi lengkap

## 🎯 Quick Command

```bash
# Lokasi folder deploy
cd /home/hasanarofid/Documents/hasanarofid/sosmed/deploy

# Upload semua isi folder ini ke:
# public_html/sosmed/ di cPanel
```

---

**Folder `deploy/` siap untuk upload! 🚀**

