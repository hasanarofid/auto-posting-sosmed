# ⚡ Quick Deploy Guide - cPanel

Panduan cepat untuk deploy ke https://sosmed.solusicodekata.com/

## 🚀 Langkah Cepat

### 1. Build Frontend

```bash
cd client
npm install
npm run build
```

### 2. Upload ke cPanel

**Via File Manager:**
1. Login cPanel → File Manager
2. Masuk ke `public_html/sosmed/`
3. Upload semua file dari `client/build/` ke root `sosmed/`
4. Upload semua file dari `server-php/` ke `sosmed/api/`

**Struktur:**
```
public_html/sosmed/
├── index.html          # Frontend
├── static/             # Frontend assets
├── .htaccess           # Frontend routing
└── api/                # Backend PHP
    ├── index.php
    ├── config.php
    ├── functions.php
    ├── routes/
    ├── uploads/
    ├── data/
    └── .htaccess
```

### 3. Setup Config

1. Di File Manager, masuk ke `api/`
2. Copy `config.local.php.example` → `config.local.php`
3. Edit `config.local.php` dan isi credentials API

### 4. Set Permissions

1. Pilih folder `api/uploads/` → Change Permissions → 755
2. Pilih folder `api/data/` → Change Permissions → 755

### 5. Update OAuth Redirect URIs

**Facebook:**
- https://developers.facebook.com/
- Facebook Login → Settings
- Tambahkan: `https://sosmed.solusicodekata.com/api/facebook/callback`

**LinkedIn:**
- https://www.linkedin.com/developers/
- Auth → Redirect URLs
- Tambahkan: `https://sosmed.solusicodekata.com/api/linkedin/callback`

**TikTok:**
- https://developers.tiktok.com/
- Redirect URI
- Tambahkan: `https://sosmed.solusicodekata.com/api/tiktok/callback`

### 6. Test

1. Buka: `https://sosmed.solusicodekata.com/`
2. Test API: `https://sosmed.solusicodekata.com/api/health`
3. Test upload dan posting

## ✅ Checklist

- [ ] Frontend di-build
- [ ] File diupload ke cPanel
- [ ] Config file dibuat dan diisi
- [ ] Permissions di-set
- [ ] OAuth Redirect URIs di-update
- [ ] SSL certificate diinstall
- [ ] Test berhasil

## 📚 Dokumentasi Lengkap

Lihat `DEPLOYMENT_CPANEL.md` untuk panduan lengkap!

