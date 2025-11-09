# 📖 Panduan Setup Lengkap

Panduan step-by-step untuk setup sistem auto-poster sosial media.

## 🎯 Overview

Sistem ini memungkinkan Anda untuk:
1. Upload gambar sekali
2. Tulis caption sekali
3. Post ke multiple platform sekaligus (Instagram, LinkedIn, Threads, TikTok)

## 📦 Step 1: Persiapan Environment

### Install Node.js

```bash
# Cek apakah Node.js sudah terinstall
node --version
npm --version

# Jika belum, install Node.js dari:
# https://nodejs.org/
# Pilih versi LTS (Long Term Support)
```

### Install Dependencies

```bash
# Masuk ke folder project
cd /home/hasanarofid/Documents/hasanarofid/sosmed

# Install dependencies untuk semua bagian
npm run install-all
```

## 🔑 Step 2: Setup API Credentials

### A. Instagram API

#### 2.1 Buat Facebook Developer Account
1. Kunjungi: https://developers.facebook.com/
2. Login dengan akun Facebook Anda
3. Klik "My Apps" → "Create App"
4. Pilih tipe: "Business"
5. Isi nama aplikasi dan email

#### 2.2 Setup Instagram Graph API
1. Di dashboard aplikasi, klik "Add Product"
2. Pilih "Instagram Graph API"
3. Klik "Set Up"
4. Ikuti wizard setup

#### 2.3 Dapatkan Access Token
1. Pergi ke "Tools" → "Graph API Explorer"
2. Pilih aplikasi Anda
3. Pilih permissions:
   - `instagram_basic`
   - `pages_show_list`
   - `pages_read_engagement`
4. Generate token
5. Copy token

#### 2.4 Dapatkan User ID
1. Di Graph API Explorer, akses:
   ```
   GET /me?fields=id,username
   ```
2. Copy `id` yang muncul

#### 2.5 Isi di .env
```env
INSTAGRAM_ACCESS_TOKEN=paste_token_di_sini
INSTAGRAM_USER_ID=paste_user_id_di_sini
INSTAGRAM_APP_ID=paste_app_id_di_sini
INSTAGRAM_APP_SECRET=paste_app_secret_di_sini
```

**Catatan:** Token Instagram biasanya expired setelah 60 hari. Perlu refresh secara berkala.

---

### B. LinkedIn API

#### 2.1 Buat LinkedIn App
1. Kunjungi: https://www.linkedin.com/developers/
2. Login dengan akun LinkedIn
3. Klik "Create app"
4. Isi:
   - App name
   - Company LinkedIn Page
   - Privacy policy URL
   - App logo
5. Klik "Create app"

#### 2.2 Setup OAuth
1. Di tab "Auth", tambahkan redirect URL:
   ```
   http://localhost:5000/api/linkedin/callback
   ```
2. Request permissions:
   - `w_member_social` (Post on behalf of user)
   - `r_liteprofile` (Read basic profile)
   - `r_emailaddress` (Read email)

#### 2.3 Generate Access Token

**Opsi 1: Menggunakan OAuth Flow (Recommended)**
1. Buat URL authorization:
   ```
   https://www.linkedin.com/oauth/v2/authorization?
   response_type=code&
   client_id=YOUR_CLIENT_ID&
   redirect_uri=http://localhost:5000/api/linkedin/callback&
   state=random_state_string&
   scope=w_member_social%20r_liteprofile
   ```
2. Buka URL di browser
3. Authorize aplikasi
4. Copy `code` dari redirect URL
5. Exchange code untuk token:
   ```bash
   curl -X POST https://www.linkedin.com/oauth/v2/accessToken \
     -d "grant_type=authorization_code" \
     -d "code=CODE_FROM_STEP_4" \
     -d "redirect_uri=http://localhost:5000/api/linkedin/callback" \
     -d "client_id=YOUR_CLIENT_ID" \
     -d "client_secret=YOUR_CLIENT_SECRET"
   ```

**Opsi 2: Menggunakan Postman**
1. Import collection LinkedIn API
2. Setup OAuth 2.0
3. Get new access token

#### 2.4 Isi di .env
```env
LINKEDIN_ACCESS_TOKEN=paste_token_di_sini
LINKEDIN_CLIENT_ID=paste_client_id_di_sini
LINKEDIN_CLIENT_SECRET=paste_client_secret_di_sini
```

---

### C. Threads API

#### 2.1 Setup Meta App
1. Gunakan aplikasi Facebook yang sama dengan Instagram
2. Atau buat aplikasi baru di https://developers.facebook.com/
3. Tambahkan produk "Threads API"

#### 2.2 Dapatkan Access Token
1. Threads menggunakan Instagram Graph API
2. Gunakan token yang sama dengan Instagram (jika menggunakan app yang sama)
3. Atau generate token baru dengan permissions untuk Threads

#### 2.3 Isi di .env
```env
THREADS_ACCESS_TOKEN=paste_token_di_sini
THREADS_APP_ID=paste_app_id_di_sini
```

---

### D. TikTok API

#### 2.1 Buat TikTok Developer Account
1. Kunjungi: https://developers.tiktok.com/
2. Login atau daftar
3. Klik "Create an app"
4. Isi informasi aplikasi

#### 2.2 Request API Access
1. Submit aplikasi untuk review
2. Tunggu approval (bisa memakan waktu 1-2 minggu)
3. Setelah approved, dapatkan credentials

#### 2.3 Generate Access Token
1. Setup OAuth 2.0 flow
2. Authorize aplikasi
3. Dapatkan access token dan open_id

#### 2.4 Isi di .env
```env
TIKTOK_ACCESS_TOKEN=paste_token_di_sini
TIKTOK_CLIENT_KEY=paste_client_key_di_sini
TIKTOK_CLIENT_SECRET=paste_client_secret_di_sini
TIKTOK_OPEN_ID=paste_open_id_di_sini
```

**Catatan:** TikTok API memerlukan approval. Untuk testing, gunakan TikTok Test Environment.

---

## 🚀 Step 3: Jalankan Aplikasi

### 3.1 Setup Environment File

```bash
# Copy example file
cp server/env.example server/.env

# Edit file .env dengan credentials Anda
nano server/.env
# atau
code server/.env
```

### 3.2 Jalankan Server dan Client

```bash
# Jalankan keduanya sekaligus
npm run dev

# Atau jalankan terpisah:
# Terminal 1
cd server && npm run dev

# Terminal 2
cd client && npm start
```

### 3.3 Akses Aplikasi

- Frontend: http://localhost:3000
- Backend API: http://localhost:5000

---

## 🧪 Step 4: Testing

### 4.1 Test Upload Gambar
1. Buka http://localhost:3000
2. Upload gambar
3. Pastikan gambar muncul di preview

### 4.2 Test API Endpoints

```bash
# Test health check
curl http://localhost:5000/api/health

# Test Instagram endpoint
curl http://localhost:5000/api/instagram/test

# Test LinkedIn endpoint
curl http://localhost:5000/api/linkedin/test

# Test Threads endpoint
curl http://localhost:5000/api/threads/test

# Test TikTok endpoint
curl http://localhost:5000/api/tiktok/test
```

### 4.3 Test Posting (dengan token valid)

1. Buka aplikasi di browser
2. Upload gambar
3. Tulis caption
4. Pilih platform
5. Klik "Post ke Semua Platform"
6. Periksa hasil di platform masing-masing

---

## 🔧 Step 5: Konfigurasi Tambahan

### 5.1 Setup Token di Browser (Development)

Untuk development, Anda bisa menyimpan token di browser localStorage:

1. Buka browser console (F12)
2. Jalankan:
```javascript
// Instagram
localStorage.setItem('instagram_token', 'YOUR_TOKEN');
localStorage.setItem('instagram_user_id', 'YOUR_USER_ID');

// LinkedIn
localStorage.setItem('linkedin_token', 'YOUR_TOKEN');

// Threads
localStorage.setItem('threads_token', 'YOUR_TOKEN');
localStorage.setItem('threads_app_id', 'YOUR_APP_ID');

// TikTok
localStorage.setItem('tiktok_token', 'YOUR_TOKEN');
localStorage.setItem('tiktok_open_id', 'YOUR_OPEN_ID');
```

### 5.2 Setup Database (Optional)

Saat ini menggunakan file JSON untuk storage. Untuk production, disarankan menggunakan database:

- PostgreSQL
- MongoDB
- MySQL

### 5.3 Setup Authentication (Production)

Untuk production, implementasikan:
- User authentication
- Secure token storage
- Session management
- Rate limiting

---

## ⚠️ Troubleshooting

### Problem: "Cannot connect to server"
**Solusi:**
- Pastikan server berjalan di port 5000
- Cek firewall settings
- Pastikan tidak ada aplikasi lain yang menggunakan port 5000

### Problem: "Access token invalid"
**Solusi:**
- Periksa token di `.env`
- Pastikan token belum expired
- Regenerate token

### Problem: "CORS error"
**Solusi:**
- Pastikan proxy di `client/package.json` sudah benar
- Pastikan backend mengizinkan CORS

### Problem: "File upload failed"
**Solusi:**
- Pastikan folder `server/uploads` ada
- Cek permissions folder
- Pastikan file size tidak melebihi 10MB

---

## 📚 Resources Tambahan

- [Instagram Graph API Documentation](https://developers.facebook.com/docs/instagram-api)
- [LinkedIn API Documentation](https://docs.microsoft.com/en-us/linkedin/)
- [Threads API Documentation](https://developers.facebook.com/docs/threads)
- [TikTok API Documentation](https://developers.tiktok.com/doc/)

---

## ✅ Checklist Setup

- [ ] Node.js terinstall
- [ ] Dependencies terinstall
- [ ] File `.env` dibuat dan diisi
- [ ] Instagram API credentials setup
- [ ] LinkedIn API credentials setup
- [ ] Threads API credentials setup
- [ ] TikTok API credentials setup (optional)
- [ ] Server berjalan
- [ ] Client berjalan
- [ ] Test upload gambar berhasil
- [ ] Test posting ke minimal 1 platform berhasil

---

Selamat! Sistem Anda sudah siap digunakan! 🎉

