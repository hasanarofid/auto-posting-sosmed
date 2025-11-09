# 📱 Sosmed Auto Poster

Sistem untuk upload konten otomatis ke Instagram, LinkedIn, Threads, dan TikTok dengan satu kali upload.

## ✨ Fitur

- 🖼️ Upload gambar dengan drag & drop
- ✍️ Editor caption yang mudah digunakan
- 🌐 Posting ke multiple platform sekaligus:
  - Instagram
  - LinkedIn
  - Threads
  - TikTok
- 👁️ Preview konten sebelum posting
- 🎨 UI modern dan responsif

## 📋 Prasyarat

- Node.js (v14 atau lebih baru)
- npm atau yarn
- Akun developer untuk setiap platform yang ingin digunakan

## 🚀 Instalasi Step-by-Step

### Step 1: Clone atau Download Project

```bash
cd /home/hasanarofid/Documents/hasanarofid/sosmed
```

### Step 2: Install Dependencies

```bash
# Install semua dependencies (root, server, dan client)
npm run install-all

# Atau install secara manual:
npm install
cd server && npm install && cd ..
cd client && npm install && cd ..
```

### Step 3: Setup Environment Variables

1. Copy file `env.example` di folder `server`:
```bash
cp server/env.example server/.env
```

2. Edit file `server/.env` dan isi dengan credentials API Anda (lihat bagian Setup API di bawah)

### Step 4: Jalankan Aplikasi

```bash
# Jalankan server dan client secara bersamaan
npm run dev

# Atau jalankan secara terpisah:
# Terminal 1 - Backend
cd server && npm run dev

# Terminal 2 - Frontend
cd client && npm start
```

Aplikasi akan berjalan di:
- Frontend: http://localhost:3000
- Backend: http://localhost:5000

## 🔧 Setup API untuk Setiap Platform

### Instagram API Setup

1. **Buat Facebook App:**
   - Kunjungi https://developers.facebook.com/
   - Buat aplikasi baru
   - Pilih "Business" sebagai tipe aplikasi

2. **Setup Instagram Basic Display atau Instagram Graph API:**
   - Tambahkan produk "Instagram Basic Display" atau "Instagram Graph API"
   - Ikuti wizard setup
   - Dapatkan `App ID` dan `App Secret`

3. **Generate Access Token:**
   - Gunakan Graph API Explorer untuk mendapatkan access token
   - Atau setup OAuth flow untuk mendapatkan token

4. **Isi di `.env`:**
```
INSTAGRAM_ACCESS_TOKEN=your_token_here
INSTAGRAM_USER_ID=your_user_id_here
INSTAGRAM_APP_ID=your_app_id_here
INSTAGRAM_APP_SECRET=your_app_secret_here
```

**Cara mendapatkan User ID:**
- Gunakan Graph API Explorer
- Akses: `https://graph.instagram.com/me?fields=id,username&access_token=YOUR_TOKEN`

### LinkedIn API Setup

1. **Buat LinkedIn App:**
   - Kunjungi https://www.linkedin.com/developers/
   - Klik "Create app"
   - Isi informasi aplikasi
   - Dapatkan `Client ID` dan `Client Secret`

2. **Setup OAuth:**
   - Tambahkan redirect URL: `http://localhost:5000/api/linkedin/callback`
   - Request permissions: `w_member_social`, `r_liteprofile`, `r_basicprofile`

3. **Generate Access Token:**
   - Gunakan OAuth 2.0 flow
   - Atau gunakan Postman untuk mendapatkan token

4. **Isi di `.env`:**
```
LINKEDIN_ACCESS_TOKEN=your_token_here
LINKEDIN_CLIENT_ID=your_client_id_here
LINKEDIN_CLIENT_SECRET=your_client_secret_here
```

### Threads API Setup

1. **Buat Meta App:**
   - Kunjungi https://developers.facebook.com/
   - Buat aplikasi baru atau gunakan yang sama dengan Instagram
   - Tambahkan produk "Threads API"

2. **Setup Threads API:**
   - Ikuti dokumentasi Threads API
   - Dapatkan access token

3. **Isi di `.env`:**
```
THREADS_ACCESS_TOKEN=your_token_here
THREADS_APP_ID=your_app_id_here
```

### TikTok API Setup

1. **Buat TikTok App:**
   - Kunjungi https://developers.tiktok.com/
   - Buat aplikasi baru
   - Dapatkan `Client Key` dan `Client Secret`

2. **Request API Access:**
   - Submit aplikasi untuk review
   - Tunggu approval (bisa memakan waktu)

3. **Generate Access Token:**
   - Setup OAuth flow
   - Dapatkan access token dan open_id

4. **Isi di `.env`:**
```
TIKTOK_ACCESS_TOKEN=your_token_here
TIKTOK_CLIENT_KEY=your_client_key_here
TIKTOK_CLIENT_SECRET=your_client_secret_here
TIKTOK_OPEN_ID=your_open_id_here
```

## 📝 Cara Menggunakan

1. **Buka aplikasi** di browser: http://localhost:3000

2. **Upload gambar:**
   - Klik area upload atau drag & drop gambar
   - Format yang didukung: JPG, PNG, GIF (Max 10MB)

3. **Tulis caption:**
   - Isi caption di textarea
   - Caption akan digunakan untuk semua platform

4. **Pilih platform:**
   - Pilih platform tempat Anda ingin memposting
   - Bisa pilih lebih dari satu platform

5. **Preview:**
   - Lihat preview konten sebelum posting

6. **Post:**
   - Klik tombol "Post ke Semua Platform"
   - Tunggu hingga proses selesai

## ⚠️ Catatan Penting

1. **Access Token:**
   - Beberapa token memiliki expiry time
   - Perlu refresh token secara berkala
   - Simpan token di localStorage browser (untuk development) atau database (untuk production)

2. **Rate Limiting:**
   - Setiap platform memiliki rate limit
   - Jangan posting terlalu sering

3. **TikTok API:**
   - TikTok API memerlukan approval dari TikTok
   - Proses approval bisa memakan waktu
   - Untuk testing, gunakan TikTok Test Environment

4. **Production:**
   - Jangan commit file `.env` ke git
   - Gunakan environment variables yang aman
   - Setup HTTPS untuk production

## 🛠️ Troubleshooting

### Error: "Cannot find module"
```bash
# Pastikan semua dependencies terinstall
npm run install-all
```

### Error: "Port already in use"
```bash
# Ubah port di server/.env atau client/package.json
PORT=5001  # untuk server
# atau edit client/package.json untuk mengubah proxy
```

### Error: "Access token invalid"
- Periksa token di `.env`
- Pastikan token belum expired
- Regenerate token jika perlu

### Error: "CORS error"
- Pastikan backend berjalan di port 5000
- Pastikan proxy di `client/package.json` sudah benar

## 📁 Struktur Project

```
sosmed/
├── client/                 # Frontend React
│   ├── public/
│   ├── src/
│   │   ├── components/    # Komponen React
│   │   ├── App.js
│   │   └── index.js
│   └── package.json
├── server/                # Backend Node.js
│   ├── routes/            # API routes
│   │   ├── instagram.js
│   │   ├── linkedin.js
│   │   ├── threads.js
│   │   ├── tiktok.js
│   │   └── content.js
│   ├── uploads/           # Folder untuk file upload
│   ├── data/              # Data storage
│   ├── index.js
│   └── package.json
├── package.json
└── README.md
```

## 🔒 Keamanan

- Jangan commit file `.env` ke repository
- Gunakan environment variables untuk production
- Implementasi authentication untuk production
- Validasi input dari user
- Rate limiting untuk API calls

## 📚 Resources

- [Instagram Graph API Docs](https://developers.facebook.com/docs/instagram-api)
- [LinkedIn API Docs](https://docs.microsoft.com/en-us/linkedin/)
- [Threads API Docs](https://developers.facebook.com/docs/threads)
- [TikTok API Docs](https://developers.tiktok.com/doc/)

## 🤝 Kontribusi

Silakan buat issue atau pull request jika ingin berkontribusi!

## 📄 License

MIT License

