# 📖 Step-by-Step: Cara Membuat Sistem Auto-Poster Sosial Media

Panduan lengkap step-by-step untuk membuat sistem upload konten otomatis ke Instagram, LinkedIn, Threads, dan TikTok.

## 🎯 Tujuan

Membuat sistem yang memungkinkan:
1. Upload gambar sekali
2. Tulis caption sekali
3. Post ke multiple platform sekaligus (Instagram, LinkedIn, Threads, TikTok)
4. UI yang mudah digunakan

## 📋 Step 1: Persiapan Project

### 1.1 Buat Struktur Folder

```bash
mkdir sosmed
cd sosmed
mkdir client server
mkdir server/routes server/uploads server/data
mkdir client/src client/public client/src/components
```

### 1.2 Initialize Node.js Project

```bash
# Root package.json
npm init -y

# Server package.json
cd server && npm init -y && cd ..

# Client package.json (akan dibuat dengan create-react-app)
```

## 📦 Step 2: Setup Backend (Node.js/Express)

### 2.1 Install Dependencies Backend

```bash
cd server
npm install express cors dotenv multer axios form-data jsonwebtoken bcryptjs sqlite3 node-cron
npm install --save-dev nodemon
cd ..
```

### 2.2 Buat Server Utama (`server/index.js`)

File ini berisi:
- Setup Express server
- Middleware (CORS, JSON parser)
- Multer untuk upload file
- Routes untuk API
- Serve static files

### 2.3 Buat API Routes

Buat file di `server/routes/`:
- `instagram.js` - API untuk posting ke Instagram
- `linkedin.js` - API untuk posting ke LinkedIn
- `threads.js` - API untuk posting ke Threads
- `tiktok.js` - API untuk posting ke TikTok
- `content.js` - API untuk manage konten

### 2.4 Setup Environment Variables

Buat file `server/env.example` dengan template:
```
PORT=5000
INSTAGRAM_ACCESS_TOKEN=...
LINKEDIN_ACCESS_TOKEN=...
THREADS_ACCESS_TOKEN=...
TIKTOK_ACCESS_TOKEN=...
```

## 🎨 Step 3: Setup Frontend (React)

### 3.1 Create React App

```bash
cd client
npx create-react-app . --template minimal
cd ..
```

Atau buat manual dengan:
- `package.json` dengan dependencies React
- Struktur folder `src/` dan `public/`

### 3.2 Install Dependencies Frontend

```bash
cd client
npm install axios react-icons
cd ..
```

### 3.3 Buat Komponen React

Buat komponen di `client/src/components/`:

1. **ContentUploader.js**
   - Upload gambar (drag & drop atau klik)
   - Textarea untuk caption
   - Preview gambar

2. **PlatformSelector.js**
   - Pilih platform (Instagram, LinkedIn, Threads, TikTok)
   - Toggle selection
   - Visual feedback

3. **ContentPreview.js**
   - Preview konten sebelum posting
   - Tampilkan gambar + caption

4. **PostButton.js**
   - Tombol untuk posting
   - Loading state
   - Disabled state

### 3.4 Buat App Utama (`client/src/App.js`)

File ini berisi:
- State management
- Handler untuk upload gambar
- Handler untuk posting
- Integrasi semua komponen

## 🔌 Step 4: Integrasi API Platform

### 4.1 Instagram API

**Setup:**
1. Buat app di https://developers.facebook.com/
2. Tambahkan produk "Instagram Graph API"
3. Dapatkan access token

**Implementasi:**
- Endpoint: `POST /api/instagram/post`
- Method: Instagram Graph API
- Upload image → Create media container → Publish

### 4.2 LinkedIn API

**Setup:**
1. Buat app di https://www.linkedin.com/developers/
2. Setup OAuth
3. Dapatkan access token

**Implementasi:**
- Endpoint: `POST /api/linkedin/post`
- Method: LinkedIn UGC API
- Upload image → Create post → Publish

### 4.3 Threads API

**Setup:**
1. Gunakan Meta app (sama dengan Instagram)
2. Tambahkan produk "Threads API"
3. Dapatkan access token

**Implementasi:**
- Endpoint: `POST /api/threads/post`
- Method: Meta Threads API
- Create thread → Publish

### 4.4 TikTok API

**Setup:**
1. Buat app di https://developers.tiktok.com/
2. Request API access
3. Tunggu approval
4. Dapatkan access token

**Implementasi:**
- Endpoint: `POST /api/tiktok/post`
- Method: TikTok Share API
- Upload video → Publish

## 🔧 Step 5: Konfigurasi

### 5.1 Setup Environment

```bash
cp server/env.example server/.env
# Edit file .env dengan credentials API
```

### 5.2 Setup Proxy (Frontend)

Edit `client/package.json`:
```json
{
  "proxy": "http://localhost:5000"
}
```

### 5.3 Setup Scripts

Edit root `package.json`:
```json
{
  "scripts": {
    "dev": "concurrently \"npm run server\" \"npm run client\"",
    "server": "cd server && nodemon index.js",
    "client": "cd client && npm start",
    "install-all": "npm install && cd server && npm install && cd ../client && npm install"
  }
}
```

## 🚀 Step 6: Testing

### 6.1 Test Backend

```bash
cd server
npm run dev
# Test di http://localhost:5000/api/health
```

### 6.2 Test Frontend

```bash
cd client
npm start
# Test di http://localhost:3000
```

### 6.3 Test Upload

1. Buka http://localhost:3000
2. Upload gambar
3. Tulis caption
4. Pilih platform
5. Klik "Post ke Semua Platform"

## 📝 Step 7: Dokumentasi

Buat file dokumentasi:
- `README.md` - Dokumentasi utama
- `SETUP_GUIDE.md` - Panduan setup lengkap
- `QUICK_START.md` - Quick start guide
- `STEP_BY_STEP.md` - File ini

## ✅ Checklist

- [ ] Struktur folder dibuat
- [ ] Backend dependencies terinstall
- [ ] Server berjalan
- [ ] API routes dibuat
- [ ] Frontend dependencies terinstall
- [ ] React components dibuat
- [ ] UI selesai
- [ ] Integrasi Instagram API
- [ ] Integrasi LinkedIn API
- [ ] Integrasi Threads API
- [ ] Integrasi TikTok API
- [ ] Environment variables setup
- [ ] Testing berhasil
- [ ] Dokumentasi selesai

## 🎨 Fitur UI

### Upload Area
- Drag & drop gambar
- Klik untuk upload
- Preview gambar
- Validasi file type dan size

### Caption Editor
- Textarea untuk caption
- Counter karakter
- Real-time preview

### Platform Selector
- Card untuk setiap platform
- Toggle selection
- Visual feedback (checkmark)
- Counter platform terpilih

### Preview Section
- Preview gambar
- Preview caption
- Tampilan seperti postingan

### Post Button
- Tombol besar dan jelas
- Loading state
- Disabled state
- Success/error message

## 🔒 Keamanan

1. **Environment Variables**
   - Jangan commit file `.env`
   - Gunakan `.gitignore`
   - Simpan credentials dengan aman

2. **Input Validation**
   - Validasi file type
   - Validasi file size
   - Sanitize caption

3. **Error Handling**
   - Try-catch untuk API calls
   - User-friendly error messages
   - Logging untuk debugging

## 🚀 Deployment (Production)

1. **Environment**
   - Setup production environment variables
   - Use HTTPS
   - Setup CORS properly

2. **Database**
   - Ganti file JSON dengan database (PostgreSQL, MongoDB, dll)
   - Setup connection pooling

3. **Authentication**
   - Implement user authentication
   - Secure token storage
   - Session management

4. **File Storage**
   - Gunakan cloud storage (AWS S3, Cloudinary, dll)
   - Setup CDN untuk static files

5. **Monitoring**
   - Setup error tracking (Sentry, dll)
   - Setup logging
   - Setup analytics

## 📚 Resources

- [Express.js Documentation](https://expressjs.com/)
- [React Documentation](https://react.dev/)
- [Instagram Graph API](https://developers.facebook.com/docs/instagram-api)
- [LinkedIn API](https://docs.microsoft.com/en-us/linkedin/)
- [Threads API](https://developers.facebook.com/docs/threads)
- [TikTok API](https://developers.tiktok.com/doc/)

---

**Selesai! Sistem auto-poster sosial media Anda sudah siap digunakan! 🎉**

