# 🚀 Quick Start Guide

Panduan cepat untuk memulai menggunakan sistem auto-poster sosial media.

## ⚡ Instalasi Cepat

### 1. Install Dependencies

```bash
npm run install-all
```

### 2. Setup Environment

```bash
# Copy file example
cp server/env.example server/.env

# Edit file .env dengan credentials API Anda
nano server/.env
```

### 3. Jalankan Aplikasi

```bash
npm run dev
```

Aplikasi akan berjalan di:
- Frontend: http://localhost:3000
- Backend: http://localhost:5000

## 📝 Cara Menggunakan

1. **Buka aplikasi** di browser: http://localhost:3000
2. **Upload gambar** dengan klik atau drag & drop
3. **Tulis caption** di textarea
4. **Pilih platform** yang ingin digunakan
5. **Klik "Post ke Semua Platform"**

## 🔑 Setup API (Minimal)

Untuk mulai testing, Anda minimal perlu setup **satu platform**:

### Instagram (Paling Mudah)

1. Buat app di https://developers.facebook.com/
2. Tambahkan produk "Instagram Graph API"
3. Dapatkan access token dari Graph API Explorer
4. Isi di `server/.env`:
   ```
   INSTAGRAM_ACCESS_TOKEN=your_token
   INSTAGRAM_USER_ID=your_user_id
   ```

### Atau LinkedIn

1. Buat app di https://www.linkedin.com/developers/
2. Setup OAuth
3. Dapatkan access token
4. Isi di `server/.env`:
   ```
   LINKEDIN_ACCESS_TOKEN=your_token
   ```

## ⚠️ Catatan

- Untuk production, setup semua platform yang ingin digunakan
- Beberapa API memerlukan approval (terutama TikTok)
- Token memiliki expiry time, perlu refresh secara berkala

## 📚 Dokumentasi Lengkap

Lihat `SETUP_GUIDE.md` untuk panduan lengkap setup semua platform.

