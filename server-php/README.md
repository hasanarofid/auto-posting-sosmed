# PHP Backend - Sosmed Auto Poster

Backend PHP untuk sistem auto-poster sosial media. Didesain untuk deployment di cPanel hosting.

## 📋 Requirements

- PHP 7.4 atau lebih baru (disarankan PHP 8.0+)
- cURL extension enabled
- JSON extension enabled
- FileInfo extension enabled
- mod_rewrite enabled (untuk Apache)

## 📁 Struktur File

```
server-php/
├── index.php                 # Main entry point
├── config.php                # Configuration loader
├── config.local.php.example  # Template config (copy ke config.local.php)
├── functions.php             # Helper functions
├── routes/                   # API routes
│   ├── health.php
│   ├── upload.php
│   ├── facebook.php
│   ├── instagram.php
│   ├── linkedin.php
│   ├── threads.php
│   ├── tiktok.php
│   └── content.php
├── uploads/                  # Folder untuk file upload
├── data/                     # Folder untuk data storage
└── .htaccess                 # Apache configuration
```

## ⚙️ Setup

### 1. Copy Config File

```bash
cp config.local.php.example config.local.php
```

### 2. Edit Config

Edit `config.local.php` dan isi dengan credentials API Anda:

```php
define('BASE_URL', 'https://sosmed.solusicodekata.com');
define('FACEBOOK_APP_ID', 'your_app_id');
// ... dll
```

### 3. Set Permissions

```bash
chmod 755 uploads/
chmod 755 data/
chmod 644 config.local.php
```

## 🚀 API Endpoints

### Health Check
```
GET /api/health
```

### Upload
```
POST /api/upload
Content-Type: multipart/form-data
Body: image (file)
```

### Facebook
```
POST /api/facebook/post
GET /api/facebook/test
```

### Instagram
```
POST /api/instagram/post
GET /api/instagram/test
```

### LinkedIn
```
POST /api/linkedin/post
GET /api/linkedin/test
```

### Threads
```
POST /api/threads/post
GET /api/threads/test
```

### TikTok
```
POST /api/tiktok/post
GET /api/tiktok/test
```

### Content
```
GET /api/content
POST /api/content
PUT /api/content/:id
DELETE /api/content/:id
```

## 📝 Notes

- File `config.local.php` tidak boleh di-commit ke git
- Pastikan folder `uploads/` dan `data/` writable
- CORS sudah di-enable untuk semua origin (ubah jika perlu untuk production)

## 🔒 Security

- File config dilindungi oleh `.htaccess`
- Input validation di setiap endpoint
- File upload validation (type dan size)
- Error messages tidak expose sensitive information

## 📚 Documentation

Lihat `DEPLOYMENT_CPANEL.md` untuk panduan deployment lengkap.

