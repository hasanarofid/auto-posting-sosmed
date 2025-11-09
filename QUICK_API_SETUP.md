# ⚡ Quick API Setup Guide

Panduan cepat untuk mendapatkan API key dan token untuk semua platform.

## 🎯 Quick Checklist

- [ ] Facebook Developer Account
- [ ] LinkedIn Developer Account  
- [ ] TikTok Developer Account
- [ ] Semua credentials diisi di `.env`

---

## 📱 1. FACEBOOK & INSTAGRAM (Satu App)

### Step 1: Buat App di Facebook Developers
1. Kunjungi: https://developers.facebook.com/
2. Login → "My Apps" → "Create App"
3. Pilih "Business"
4. Isi nama app dan email

### Step 2: Tambahkan Products
- Facebook Login
- Instagram Basic Display
- Instagram Graph API

### Step 3: Dapatkan Credentials
1. Settings → Basic:
   - **App ID** → Copy
   - **App Secret** → Show & Copy

2. Graph API Explorer:
   - Tools → Graph API Explorer
   - Pilih app Anda
   - Get Token → Get User Access Token
   - Pilih permissions: `pages_manage_posts`, `instagram_basic`, `instagram_content_publish`
   - Generate token
   - Get Long-Lived Token (expired 60 hari)

3. Dapatkan Page ID:
   ```bash
   curl "https://graph.facebook.com/v18.0/me/accounts?access_token=YOUR_TOKEN"
   ```

4. Dapatkan Page Access Token:
   ```bash
   curl "https://graph.facebook.com/v18.0/PAGE_ID?fields=access_token&access_token=YOUR_TOKEN"
   ```

5. Dapatkan Instagram Business Account ID:
   ```bash
   curl "https://graph.facebook.com/v18.0/PAGE_ID?fields=instagram_business_account&access_token=PAGE_TOKEN"
   ```

### Step 4: Isi di `.env`
```env
FACEBOOK_APP_ID=your_app_id
FACEBOOK_APP_SECRET=your_app_secret
FACEBOOK_ACCESS_TOKEN=your_long_lived_token
FACEBOOK_PAGE_ID=your_page_id
FACEBOOK_PAGE_ACCESS_TOKEN=your_page_token

INSTAGRAM_ACCESS_TOKEN=your_page_token
INSTAGRAM_USER_ID=your_instagram_business_account_id
INSTAGRAM_APP_ID=your_app_id
INSTAGRAM_APP_SECRET=your_app_secret
```

---

## 💼 2. LINKEDIN

### Step 1: Buat App
1. Kunjungi: https://www.linkedin.com/developers/
2. Login → "Create app"
3. Isi informasi app

### Step 2: Dapatkan Credentials
1. Tab "Auth":
   - **Client ID** → Copy
   - **Client Secret** → Show & Copy

2. Tambahkan Redirect URI:
   ```
   http://localhost:5000/api/linkedin/callback
   ```

3. Request Permissions:
   - `w_member_social` (Post on behalf of user)
   - `r_liteprofile` (Read profile)

### Step 3: Dapatkan Token
1. Buka URL ini (ganti YOUR_CLIENT_ID):
   ```
   https://www.linkedin.com/oauth/v2/authorization?
   response_type=code&
   client_id=YOUR_CLIENT_ID&
   redirect_uri=http://localhost:5000/api/linkedin/callback&
   scope=w_member_social%20r_liteprofile
   ```

2. Authorize → Copy `code` dari URL callback

3. Exchange code untuk token:
   ```bash
   curl -X POST https://www.linkedin.com/oauth/v2/accessToken \
     -d "grant_type=authorization_code" \
     -d "code=CODE_FROM_CALLBACK" \
     -d "redirect_uri=http://localhost:5000/api/linkedin/callback" \
     -d "client_id=YOUR_CLIENT_ID" \
     -d "client_secret=YOUR_CLIENT_SECRET"
   ```

4. Dapatkan Person URN:
   ```bash
   curl "https://api.linkedin.com/v2/me" \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

### Step 4: Isi di `.env`
```env
LINKEDIN_ACCESS_TOKEN=your_token
LINKEDIN_CLIENT_ID=your_client_id
LINKEDIN_CLIENT_SECRET=your_client_secret
LINKEDIN_PERSON_URN=your_person_urn
```

---

## 🧵 3. THREADS

Threads menggunakan Meta app yang sama dengan Facebook/Instagram.

### Step 1: Tambahkan Threads API
1. Di Meta app dashboard → "Add Product"
2. Pilih "Threads API"
3. Setup

### Step 2: Dapatkan Token
- Gunakan Page Access Token yang sama dengan Instagram
- Atau generate token baru dengan permissions Threads

### Step 3: Dapatkan Threads User ID
```bash
curl "https://graph.threads.net/v1.0/me?fields=id&access_token=YOUR_TOKEN"
```

### Step 4: Isi di `.env`
```env
THREADS_ACCESS_TOKEN=your_page_token
THREADS_APP_ID=your_app_id
THREADS_USER_ID=your_threads_user_id
```

---

## 🎵 4. TIKTOK

### Step 1: Buat App
1. Kunjungi: https://developers.tiktok.com/
2. Login → "Create an app"
3. Isi informasi app

### Step 2: Request Access
1. Klik "Request Access"
2. Isi form use case
3. Submit → Tunggu approval (1-2 minggu)

### Step 3: Dapatkan Credentials
1. Basic Information:
   - **Client Key** → Copy
   - **Client Secret** → Show & Copy

2. Tambahkan Redirect URI:
   ```
   http://localhost:5000/api/tiktok/callback
   ```

### Step 4: Dapatkan Token (Setelah Approved)
1. Buka URL ini (ganti YOUR_CLIENT_KEY):
   ```
   https://www.tiktok.com/v2/auth/authorize?
   client_key=YOUR_CLIENT_KEY&
   scope=user.info.basic,video.upload&
   response_type=code&
   redirect_uri=http://localhost:5000/api/tiktok/callback
   ```

2. Authorize → Copy `code` dari URL callback

3. Exchange code untuk token:
   ```bash
   curl -X POST "https://open.tiktokapis.com/v2/oauth/token/" \
     -d "client_key=YOUR_CLIENT_KEY" \
     -d "client_secret=YOUR_CLIENT_SECRET" \
     -d "code=CODE_FROM_CALLBACK" \
     -d "grant_type=authorization_code" \
     -d "redirect_uri=http://localhost:5000/api/tiktok/callback"
   ```

4. Response berisi `access_token` dan `open_id`

### Step 5: Isi di `.env`
```env
TIKTOK_ACCESS_TOKEN=your_token
TIKTOK_CLIENT_KEY=your_client_key
TIKTOK_CLIENT_SECRET=your_client_secret
TIKTOK_OPEN_ID=your_open_id
```

---

## 📝 5. IMPLEMENTASI DI PROJECT

### Step 1: Update `.env`
```bash
cp server/env.example server/.env
# Edit server/.env dengan semua credentials di atas
```

### Step 2: Test API
```bash
# Test Facebook
curl http://localhost:5000/api/facebook/test

# Test Instagram
curl http://localhost:5000/api/instagram/test

# Test LinkedIn
curl http://localhost:5000/api/linkedin/test

# Test Threads
curl http://localhost:5000/api/threads/test

# Test TikTok
curl http://localhost:5000/api/tiktok/test
```

### Step 3: Setup Token di Browser (Development)
Buka browser console (F12) dan jalankan:

```javascript
// Facebook
localStorage.setItem('facebook_token', 'YOUR_PAGE_TOKEN');
localStorage.setItem('facebook_page_id', 'YOUR_PAGE_ID');

// Instagram
localStorage.setItem('instagram_token', 'YOUR_PAGE_TOKEN');
localStorage.setItem('instagram_user_id', 'YOUR_INSTAGRAM_BUSINESS_ACCOUNT_ID');

// LinkedIn
localStorage.setItem('linkedin_token', 'YOUR_TOKEN');

// Threads
localStorage.setItem('threads_token', 'YOUR_PAGE_TOKEN');
localStorage.setItem('threads_app_id', 'YOUR_APP_ID');

// TikTok
localStorage.setItem('tiktok_token', 'YOUR_TOKEN');
localStorage.setItem('tiktok_open_id', 'YOUR_OPEN_ID');
```

---

## ✅ Checklist Lengkap

### Facebook & Instagram
- [ ] Meta app dibuat
- [ ] Products ditambahkan (Facebook Login, Instagram Graph API)
- [ ] App ID & Secret didapatkan
- [ ] Long-lived token didapatkan
- [ ] Page ID didapatkan
- [ ] Page Access Token didapatkan
- [ ] Instagram Business Account ID didapatkan
- [ ] Credentials diisi di `.env`

### LinkedIn
- [ ] LinkedIn app dibuat
- [ ] Client ID & Secret didapatkan
- [ ] Redirect URI di-setup
- [ ] Permissions di-request
- [ ] Access token didapatkan
- [ ] Person URN didapatkan
- [ ] Credentials diisi di `.env`

### Threads
- [ ] Threads API ditambahkan di Meta app
- [ ] Access token didapatkan
- [ ] Threads User ID didapatkan
- [ ] Credentials diisi di `.env`

### TikTok
- [ ] TikTok app dibuat
- [ ] Access di-request
- [ ] Approval diterima
- [ ] Client Key & Secret didapatkan
- [ ] Access token didapatkan
- [ ] Open ID didapatkan
- [ ] Credentials diisi di `.env`

---

## 🚀 Quick Start

Setelah semua credentials diisi:

```bash
# Jalankan aplikasi
npm run dev

# Buka browser
http://localhost:3000
```

---

**Lihat `API_SETUP_COMPLETE.md` untuk panduan lengkap step-by-step!**

