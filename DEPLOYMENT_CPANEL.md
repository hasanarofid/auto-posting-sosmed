# 🚀 Tutorial Deployment Lengkap ke cPanel

Panduan step-by-step untuk deploy sistem auto-poster sosial media ke cPanel (https://sosmed.solusicodekata.com/).

## 📋 Prasyarat

- [ ] Akun cPanel hosting
- [ ] Domain sudah terhubung (sosmed.solusicodekata.com)
- [ ] File Manager atau FTP access
- [ ] PHP 7.4 atau lebih baru
- [ ] cURL extension enabled di PHP

## 📦 Step 1: Persiapan File

### 1.1 Siapkan File untuk Upload

File yang perlu diupload:
```
sosmed/
├── client/              # Frontend React (build)
├── server-php/          # Backend PHP
│   ├── index.php
│   ├── config.php
│   ├── functions.php
│   ├── routes/
│   ├── uploads/
│   ├── data/
│   └── .htaccess
└── DEPLOYMENT_CPANEL.md
```

### 1.2 Build Frontend React

```bash
cd client
npm install
npm run build
```

File build akan ada di folder `client/build/`

## 📤 Step 2: Upload ke cPanel

### 2.1 Login ke cPanel

1. Buka: `https://cpanel.solusicodekata.com` (atau URL cPanel Anda)
2. Login dengan username dan password

### 2.2 Akses File Manager

1. Di cPanel, cari **"File Manager"**
2. Klik untuk membuka
3. Pilih **"public_html"** atau **"www"** sebagai root directory

### 2.3 Buat Folder untuk Aplikasi

1. Di File Manager, klik **"New Folder"**
2. Buat folder: `sosmed` (atau sesuai kebutuhan)
3. Masuk ke folder tersebut

### 2.4 Upload Backend PHP

1. Di dalam folder `sosmed`, buat folder: `api`
2. Upload semua file dari `server-php/` ke folder `api/`:
   - `index.php`
   - `config.php`
   - `functions.php`
   - `routes/` (folder beserta isinya)
   - `.htaccess`

**Struktur di cPanel:**
```
public_html/
└── sosmed/
    └── api/
        ├── index.php
        ├── config.php
        ├── functions.php
        ├── routes/
        ├── uploads/
        ├── data/
        └── .htaccess
```

### 2.5 Upload Frontend

1. Di dalam folder `sosmed`, upload semua file dari `client/build/`:
   - `index.html`
   - `static/` (folder)
   - File-file lainnya

**Struktur lengkap:**
```
public_html/
└── sosmed/
    ├── api/              # Backend PHP
    │   ├── index.php
    │   ├── config.php
    │   ├── functions.php
    │   ├── routes/
    │   ├── uploads/
    │   ├── data/
    │   └── .htaccess
    ├── index.html        # Frontend
    ├── static/
    └── ...
```

### 2.6 Set Permissions

1. Pilih folder `api/uploads/`
2. Klik **"Change Permissions"** atau **"Permissions"**
3. Set permissions: **755** (atau **rwxr-xr-x**)
4. Ulangi untuk folder `api/data/`

## ⚙️ Step 3: Konfigurasi

### 3.1 Buat File Konfigurasi

1. Di File Manager, masuk ke folder `api/`
2. Copy file `config.local.php.example` menjadi `config.local.php`
3. Edit file `config.local.php` dengan File Manager editor

### 3.2 Isi Konfigurasi

Edit `api/config.local.php`:

```php
<?php
// Base URL
define('BASE_URL', 'https://sosmed.solusicodekata.com');

// Directories (gunakan path absolut)
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('DATA_DIR', __DIR__ . '/data');

// Facebook API
define('FACEBOOK_APP_ID', 'your_facebook_app_id');
define('FACEBOOK_APP_SECRET', 'your_facebook_app_secret');
define('FACEBOOK_ACCESS_TOKEN', 'your_facebook_access_token');
define('FACEBOOK_PAGE_ID', 'your_facebook_page_id');
define('FACEBOOK_PAGE_ACCESS_TOKEN', 'your_facebook_page_access_token');

// Instagram API
define('INSTAGRAM_ACCESS_TOKEN', 'your_instagram_access_token');
define('INSTAGRAM_USER_ID', 'your_instagram_user_id');
define('INSTAGRAM_APP_ID', 'your_instagram_app_id');
define('INSTAGRAM_APP_SECRET', 'your_instagram_app_secret');

// LinkedIn API
define('LINKEDIN_ACCESS_TOKEN', 'your_linkedin_access_token');
define('LINKEDIN_CLIENT_ID', 'your_linkedin_client_id');
define('LINKEDIN_CLIENT_SECRET', 'your_linkedin_client_secret');
define('LINKEDIN_PERSON_URN', 'your_linkedin_person_urn');

// Threads API
define('THREADS_ACCESS_TOKEN', 'your_threads_access_token');
define('THREADS_APP_ID', 'your_threads_app_id');
define('THREADS_USER_ID', 'your_threads_user_id');

// TikTok API
define('TIKTOK_ACCESS_TOKEN', 'your_tiktok_access_token');
define('TIKTOK_CLIENT_KEY', 'your_tiktok_client_key');
define('TIKTOK_CLIENT_SECRET', 'your_tiktok_client_secret');
define('TIKTOK_OPEN_ID', 'your_tiktok_open_id');
```

**Ganti semua `your_*` dengan credentials API Anda!**

### 3.3 Update OAuth Redirect URIs di Facebook Developer

1. Buka: https://developers.facebook.com/
2. Pilih aplikasi Anda
3. Masuk ke **"Facebook Login"** → **"Settings"**
4. Di bagian **"Valid OAuth Redirect URIs"**, tambahkan:
   ```
   https://sosmed.solusicodekata.com/api/facebook/callback
   https://sosmed.solusicodekata.com/api/instagram/callback
   ```
5. Klik **"Save Changes"**

### 3.4 Update OAuth Redirect URIs di LinkedIn

1. Buka: https://www.linkedin.com/developers/
2. Pilih aplikasi Anda
3. Tab **"Auth"**
4. Di bagian **"Redirect URLs"**, tambahkan:
   ```
   https://sosmed.solusicodekata.com/api/linkedin/callback
   ```
5. Klik **"Update"**

### 3.5 Update OAuth Redirect URIs di TikTok

1. Buka: https://developers.tiktok.com/
2. Pilih aplikasi Anda
3. Di bagian **"Redirect URI"**, tambahkan:
   ```
   https://sosmed.solusicodekata.com/api/tiktok/callback
   ```
4. Klik **"Save"**

## 🔧 Step 4: Konfigurasi cPanel

### 4.1 Setup Subdomain (Jika Perlu)

Jika menggunakan subdomain `sosmed.solusicodekata.com`:

1. Di cPanel, cari **"Subdomains"**
2. Buat subdomain: `sosmed`
3. Document Root: `/public_html/sosmed` (atau sesuai)
4. Klik **"Create"**

### 4.2 Setup SSL Certificate

1. Di cPanel, cari **"SSL/TLS"** atau **"Let's Encrypt SSL"**
2. Pilih domain: `sosmed.solusicodekata.com`
3. Install SSL certificate (gratis dengan Let's Encrypt)
4. Klik **"Install"**

### 4.3 Cek PHP Version

1. Di cPanel, cari **"Select PHP Version"** atau **"MultiPHP Manager"**
2. Pilih domain: `sosmed.solusicodekata.com`
3. Pilih PHP version: **7.4** atau lebih baru (disarankan **8.0+**)
4. Klik **"Set as current"**

### 4.4 Enable PHP Extensions

1. Di **"Select PHP Version"**, klik **"Extensions"**
2. Pastikan extension berikut enabled:
   - ✅ `curl`
   - ✅ `json`
   - ✅ `fileinfo`
   - ✅ `mbstring`
3. Klik **"Save"**

### 4.5 Setup .htaccess untuk Frontend

Buat file `.htaccess` di folder `sosmed/` (root aplikasi):

```apache
# Enable Rewrite Engine
RewriteEngine On

# Redirect all requests to index.html (for React Router)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/api/
RewriteRule ^ index.html [L]

# Security: Deny access to sensitive files
<FilesMatch "^(\.env|\.git|config\.local\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## 🔗 Step 5: Update Frontend untuk Production

### 5.1 Update API Base URL

Edit file `client/src/App.js` sebelum build:

```javascript
// Ganti base URL untuk production
const API_BASE_URL = process.env.NODE_ENV === 'production' 
  ? 'https://sosmed.solusicodekata.com/api'
  : 'http://localhost:5000/api';
```

Atau buat file `.env.production` di folder `client/`:

```
REACT_APP_API_URL=https://sosmed.solusicodekata.com/api
```

Lalu update `client/src/App.js`:

```javascript
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:5000/api';
```

### 5.2 Rebuild Frontend

```bash
cd client
npm run build
```

### 5.3 Upload Build Files

Upload semua file dari `client/build/` ke folder `sosmed/` di cPanel.

## ✅ Step 6: Testing

### 6.1 Test Backend API

Buka browser dan test endpoint:

1. **Health Check:**
   ```
   https://sosmed.solusicodekata.com/api/health
   ```
   Harus return: `{"status":"OK","message":"Server berjalan dengan baik"}`

2. **Test Facebook API:**
   ```
   https://sosmed.solusicodekata.com/api/facebook/test
   ```

3. **Test Instagram API:**
   ```
   https://sosmed.solusicodekata.com/api/instagram/test
   ```

4. **Test LinkedIn API:**
   ```
   https://sosmed.solusicodekata.com/api/linkedin/test
   ```

5. **Test Threads API:**
   ```
   https://sosmed.solusicodekata.com/api/threads/test
   ```

6. **Test TikTok API:**
   ```
   https://sosmed.solusicodekata.com/api/tiktok/test
   ```

### 6.2 Test Frontend

1. Buka: `https://sosmed.solusicodekata.com/`
2. Pastikan halaman load dengan benar
3. Test upload gambar
4. Test posting ke platform

### 6.3 Test Upload

1. Buka aplikasi di browser
2. Upload gambar
3. Pastikan file tersimpan di `api/uploads/`
4. Cek permissions folder `uploads/` (harus writable)

## 🔒 Step 7: Security

### 7.1 Protect Config File

1. Pastikan file `config.local.php` tidak bisa diakses dari browser
2. File `.htaccess` sudah mengatur deny access ke config files

### 7.2 Set Proper Permissions

```bash
# Via SSH atau File Manager
chmod 644 api/config.local.php
chmod 755 api/uploads/
chmod 755 api/data/
chmod 644 api/.htaccess
```

### 7.3 Disable Error Display (Production)

Edit `api/.htaccess`:

```apache
# Disable error display in production
php_flag display_errors Off
php_flag log_errors On
```

## 🐛 Troubleshooting

### Problem: "404 Not Found" saat akses API

**Solusi:**
1. Pastikan file `.htaccess` ada di folder `api/`
2. Pastikan RewriteEngine On di `.htaccess`
3. Cek apakah mod_rewrite enabled di cPanel
4. Cek path di `.htaccess` sesuai struktur folder

### Problem: "500 Internal Server Error"

**Solusi:**
1. Cek error log di cPanel → **"Error Log"**
2. Pastikan PHP version 7.4+
3. Pastikan semua extensions enabled
4. Cek permissions folder `uploads/` dan `data/`

### Problem: "CORS Error"

**Solusi:**
1. Pastikan CORS headers di `index.php` sudah benar
2. Cek apakah `.htaccess` tidak memblokir headers

### Problem: "File Upload Failed"

**Solusi:**
1. Cek permissions folder `uploads/` (harus 755 atau 777)
2. Cek PHP upload limits di `.htaccess`
3. Cek disk space di cPanel

### Problem: "API credentials tidak bekerja"

**Solusi:**
1. Pastikan `config.local.php` sudah diisi dengan benar
2. Pastikan tidak ada typo di credentials
3. Cek OAuth Redirect URIs sudah di-update di developer console

## 📝 Checklist Deployment

- [ ] File backend PHP diupload ke `api/`
- [ ] File frontend diupload ke root `sosmed/`
- [ ] Folder `uploads/` dan `data/` dibuat dengan permissions 755
- [ ] File `config.local.php` dibuat dan diisi credentials
- [ ] OAuth Redirect URIs di-update di semua platform
- [ ] SSL certificate diinstall
- [ ] PHP version 7.4+ dipilih
- [ ] PHP extensions enabled (curl, json, dll)
- [ ] `.htaccess` untuk frontend dibuat
- [ ] `.htaccess` untuk backend dibuat
- [ ] Test semua API endpoints
- [ ] Test frontend aplikasi
- [ ] Test upload gambar
- [ ] Test posting ke platform

## 🎉 Selesai!

Aplikasi Anda sekarang sudah online di:
**https://sosmed.solusicodekata.com/**

## 📚 Resources

- [cPanel Documentation](https://docs.cpanel.net/)
- [PHP cURL Documentation](https://www.php.net/manual/en/book.curl.php)
- [Apache .htaccess Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)

---

**Selamat! Sistem auto-poster sosial media Anda sudah online! 🚀**

