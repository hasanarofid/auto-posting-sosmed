# 🔑 Panduan Lengkap Setup API Key & Token untuk Semua Platform

Panduan step-by-step lengkap untuk mendapatkan API key dan token untuk Instagram, LinkedIn, Threads, TikTok, dan Facebook.

---

## 📱 1. FACEBOOK & INSTAGRAM API SETUP

Facebook dan Instagram menggunakan sistem yang sama (Meta Developer Platform). Satu app bisa digunakan untuk Facebook dan Instagram.

### Step 1.1: Buat Facebook Developer Account

1. **Kunjungi Facebook Developers:**
   - Buka: https://developers.facebook.com/
   - Login dengan akun Facebook Anda
   - Jika belum punya akun developer, daftar dulu

2. **Buat Aplikasi Baru:**
   - Klik menu **"My Apps"** di pojok kanan atas
   - Klik **"Create App"**
   - Pilih tipe aplikasi: **"Business"** (untuk posting ke Facebook/Instagram)
   - Klik **"Next"**

3. **Isi Informasi Aplikasi:**
   - **App Name:** Nama aplikasi Anda (contoh: "Sosmed Auto Poster")
   - **App Contact Email:** Email Anda
   - **Business Account:** Pilih atau buat business account
   - Klik **"Create App"**

4. **Selesaikan Security Check:**
   - Facebook akan meminta verifikasi (captcha atau SMS)
   - Selesaikan verifikasi

### Step 1.2: Setup Facebook Login & Instagram Basic Display

1. **Di Dashboard Aplikasi:**
   - Anda akan melihat dashboard aplikasi
   - Di sidebar kiri, klik **"Add Product"**

2. **Tambahkan Facebook Login:**
   - Cari **"Facebook Login"**
   - Klik **"Set Up"**
   - Pilih **"Web"** sebagai platform
   - Klik **"Next"** → **"Skip Quick Start"**

3. **Tambahkan Instagram Basic Display:**
   - Kembali ke dashboard
   - Klik **"Add Product"** lagi
   - Cari **"Instagram Basic Display"**
   - Klik **"Set Up"**
   - Pilih **"Web"** sebagai platform
   - Klik **"Next"** → **"Skip Quick Start"**

4. **Tambahkan Instagram Graph API:**
   - Kembali ke dashboard
   - Klik **"Add Product"** lagi
   - Cari **"Instagram Graph API"**
   - Klik **"Set Up"**
   - Pilih **"Web"** sebagai platform
   - Klik **"Next"** → **"Skip Quick Start"**

### Step 1.3: Dapatkan App ID dan App Secret

1. **Di Dashboard:**
   - Klik **"Settings"** → **"Basic"** di sidebar kiri
   - Anda akan melihat:
     - **App ID** - Copy ini
     - **App Secret** - Klik **"Show"** dan copy (simpan dengan aman!)

2. **Tambahkan Platform:**
   - Scroll ke bawah ke bagian **"Platform"**
   - Klik **"Add Platform"**
   - Pilih **"Website"**
   - Isi **Site URL:** `http://localhost:5000` (untuk development)

### Step 1.4: Setup OAuth Redirect URIs

1. **Facebook Login Settings:**
   - Klik **"Facebook Login"** → **"Settings"** di sidebar
   - Di bagian **"Valid OAuth Redirect URIs"**, tambahkan:
     ```
     http://localhost:5000/api/facebook/callback
     http://localhost:5000/api/instagram/callback
     ```

2. **Instagram Basic Display Settings:**
   - Klik **"Instagram Basic Display"** → **"Settings"**
   - Di bagian **"Valid OAuth Redirect URIs"**, tambahkan:
     ```
     http://localhost:5000/api/instagram/callback
     ```

3. **Klik "Save Changes"**

### Step 1.5: Request Permissions

1. **Facebook Login Permissions:**
   - Klik **"Facebook Login"** → **"Permissions and Features"**
   - Request permissions berikut:
     - `pages_manage_posts` (Post to Facebook Pages)
     - `pages_read_engagement` (Read engagement data)
     - `pages_show_list` (Show list of pages)
     - `publish_to_groups` (Post to groups - optional)
     - `user_posts` (Access user posts)

2. **Instagram Graph API Permissions:**
   - Klik **"Instagram Graph API"** → **"Permissions and Features"**
   - Request permissions:
     - `instagram_basic` (Basic Instagram access)
     - `instagram_content_publish` (Publish to Instagram)
     - `pages_show_list` (Show list of pages)
     - `pages_read_engagement` (Read engagement)

3. **Submit for Review (Jika Perlu):**
   - Beberapa permissions memerlukan review dari Facebook
   - Klik **"App Review"** → **"Permissions and Features"**
   - Request review untuk permissions yang memerlukan approval

### Step 1.6: Dapatkan Access Token (Development)

**Opsi 1: Menggunakan Graph API Explorer (Paling Mudah untuk Testing)**

1. **Buka Graph API Explorer:**
   - Kunjungi: https://developers.facebook.com/tools/explorer/
   - Atau dari dashboard: **"Tools"** → **"Graph API Explorer"**

2. **Pilih Aplikasi:**
   - Di dropdown **"Meta App"**, pilih aplikasi Anda
   - Di dropdown **"User or Page"**, pilih **"Me"** atau halaman Facebook Anda

3. **Pilih Permissions:**
   - Klik **"Get Token"** → **"Get User Access Token"**
   - Pilih permissions:
     - `pages_manage_posts`
     - `pages_read_engagement`
     - `pages_show_list`
     - `instagram_basic`
     - `instagram_content_publish`
   - Klik **"Generate Access Token"**

4. **Authorize:**
   - Facebook akan meminta authorization
   - Klik **"Continue"** → **"OK"**

5. **Copy Token:**
   - Token akan muncul di field **"Access Token"**
   - **Copy token ini** (token ini expired setelah beberapa jam)

6. **Generate Long-Lived Token:**
   - Di Graph API Explorer, klik **"Get Token"** → **"Get Long-Lived Token"**
   - Token baru akan muncul (expired setelah 60 hari)
   - **Copy token ini**

**Opsi 2: Menggunakan OAuth Flow (Recommended untuk Production)**

1. **Buat Authorization URL:**
   ```
   https://www.facebook.com/v18.0/dialog/oauth?
   client_id=YOUR_APP_ID&
   redirect_uri=http://localhost:5000/api/facebook/callback&
   scope=pages_manage_posts,pages_read_engagement,pages_show_list,instagram_basic,instagram_content_publish&
   response_type=code
   ```

2. **Buka URL di Browser:**
   - Ganti `YOUR_APP_ID` dengan App ID Anda
   - Buka URL di browser
   - Authorize aplikasi
   - Anda akan di-redirect ke callback URL dengan `code`

3. **Exchange Code untuk Token:**
   ```bash
   curl -X GET "https://graph.facebook.com/v18.0/oauth/access_token?\
   client_id=YOUR_APP_ID&\
   client_secret=YOUR_APP_SECRET&\
   redirect_uri=http://localhost:5000/api/facebook/callback&\
   code=CODE_FROM_CALLBACK"
   ```

4. **Exchange Short-Lived Token untuk Long-Lived Token:**
   ```bash
   curl -X GET "https://graph.facebook.com/v18.0/oauth/access_token?\
   grant_type=fb_exchange_token&\
   client_id=YOUR_APP_ID&\
   client_secret=YOUR_APP_SECRET&\
   fb_exchange_token=SHORT_LIVED_TOKEN"
   ```

### Step 1.7: Dapatkan Page ID dan Instagram Business Account ID

1. **Dapatkan Page ID:**
   ```bash
   curl -X GET "https://graph.facebook.com/v18.0/me/accounts?\
   access_token=YOUR_ACCESS_TOKEN"
   ```
   - Response akan berisi list pages
   - Copy `id` dari page yang ingin digunakan

2. **Dapatkan Page Access Token:**
   ```bash
   curl -X GET "https://graph.facebook.com/v18.0/PAGE_ID?\
   fields=access_token&\
   access_token=YOUR_ACCESS_TOKEN"
   ```
   - Copy `access_token` dari response

3. **Dapatkan Instagram Business Account ID:**
   ```bash
   curl -X GET "https://graph.facebook.com/v18.0/PAGE_ID?\
   fields=instagram_business_account&\
   access_token=PAGE_ACCESS_TOKEN"
   ```
   - Copy `id` dari `instagram_business_account`

### Step 1.8: Implementasi di Project

1. **Update `server/.env`:**
   ```env
   # Facebook API
   FACEBOOK_APP_ID=your_app_id_here
   FACEBOOK_APP_SECRET=your_app_secret_here
   FACEBOOK_ACCESS_TOKEN=your_long_lived_token_here
   FACEBOOK_PAGE_ID=your_page_id_here
   FACEBOOK_PAGE_ACCESS_TOKEN=your_page_access_token_here

   # Instagram API
   INSTAGRAM_ACCESS_TOKEN=your_page_access_token_here
   INSTAGRAM_USER_ID=your_instagram_business_account_id_here
   INSTAGRAM_APP_ID=your_app_id_here
   INSTAGRAM_APP_SECRET=your_app_secret_here
   ```

2. **Test API:**
   ```bash
   # Test Facebook API
   curl http://localhost:5000/api/facebook/test

   # Test Instagram API
   curl http://localhost:5000/api/instagram/test
   ```

---

## 💼 2. LINKEDIN API SETUP

### Step 2.1: Buat LinkedIn Developer Account

1. **Kunjungi LinkedIn Developers:**
   - Buka: https://www.linkedin.com/developers/
   - Login dengan akun LinkedIn Anda

2. **Buat Aplikasi:**
   - Klik **"Create app"**
   - Isi informasi:
     - **App name:** Nama aplikasi Anda
     - **LinkedIn Page:** Pilih atau buat LinkedIn Page
     - **Privacy policy URL:** URL privacy policy Anda (bisa dummy untuk testing)
     - **App logo:** Upload logo (optional)
   - Centang **"I agree to the LinkedIn API Terms of Use"**
   - Klik **"Create app"**

### Step 2.2: Dapatkan Client ID dan Client Secret

1. **Di Dashboard Aplikasi:**
   - Klik tab **"Auth"**
   - Anda akan melihat:
     - **Client ID** - Copy ini
     - **Client Secret** - Klik **"Show"** dan copy (simpan dengan aman!)

### Step 2.3: Setup OAuth Redirect URIs

1. **Di Tab "Auth":**
   - Scroll ke bagian **"Redirect URLs"**
   - Klik **"Add redirect URL"**
   - Tambahkan:
     ```
     http://localhost:5000/api/linkedin/callback
     ```
   - Klik **"Update"**

### Step 2.4: Request Permissions

1. **Di Tab "Auth":**
   - Scroll ke bagian **"Products"**
   - Request products berikut:
     - **"Sign In with LinkedIn using OpenID Connect"** (optional)
     - **"Share on LinkedIn"** (wajib untuk posting)
     - **"Marketing Developer Platform"** (optional)

2. **Request Permissions:**
   - Scroll ke bagian **"OAuth 2.0 scopes"**
   - Request scopes berikut:
     - `w_member_social` - Post on behalf of user
     - `r_liteprofile` - Read basic profile
     - `r_emailaddress` - Read email address
     - `w_organization_social` - Post on behalf of organization (optional)

3. **Submit for Review:**
   - Beberapa permissions memerlukan review
   - Klik **"Request"** untuk permissions yang memerlukan approval
   - Tunggu approval (bisa memakan waktu beberapa hari)

### Step 2.5: Dapatkan Access Token

**Opsi 1: Menggunakan OAuth Flow (Recommended)**

1. **Buat Authorization URL:**
   ```
   https://www.linkedin.com/oauth/v2/authorization?
   response_type=code&
   client_id=YOUR_CLIENT_ID&
   redirect_uri=http://localhost:5000/api/linkedin/callback&
   state=random_state_string&
   scope=w_member_social%20r_liteprofile%20r_emailaddress
   ```

2. **Buka URL di Browser:**
   - Ganti `YOUR_CLIENT_ID` dengan Client ID Anda
   - Buka URL di browser
   - Login dan authorize aplikasi
   - Anda akan di-redirect ke callback URL dengan `code`

3. **Exchange Code untuk Token:**
   ```bash
   curl -X POST https://www.linkedin.com/oauth/v2/accessToken \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "grant_type=authorization_code" \
     -d "code=CODE_FROM_CALLBACK" \
     -d "redirect_uri=http://localhost:5000/api/linkedin/callback" \
     -d "client_id=YOUR_CLIENT_ID" \
     -d "client_secret=YOUR_CLIENT_SECRET"
   ```

4. **Response akan berisi:**
   ```json
   {
     "access_token": "your_access_token",
     "expires_in": 5184000,
     "refresh_token": "your_refresh_token"
   }
   ```

**Opsi 2: Menggunakan Postman**

1. **Import LinkedIn Collection:**
   - Buka Postman
   - Import collection LinkedIn API
   - Setup OAuth 2.0
   - Get new access token

### Step 2.6: Dapatkan User Profile (Person URN)

1. **Get User Profile:**
   ```bash
   curl -X GET "https://api.linkedin.com/v2/me" \
     -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
   ```

2. **Response akan berisi:**
   ```json
   {
     "id": "person_urn_here"
   }
   ```
   - Copy `id` ini (Person URN)

### Step 2.7: Implementasi di Project

1. **Update `server/.env`:**
   ```env
   # LinkedIn API
   LINKEDIN_ACCESS_TOKEN=your_access_token_here
   LINKEDIN_CLIENT_ID=your_client_id_here
   LINKEDIN_CLIENT_SECRET=your_client_secret_here
   LINKEDIN_PERSON_URN=your_person_urn_here
   ```

2. **Test API:**
   ```bash
   curl http://localhost:5000/api/linkedin/test
   ```

---

## 🧵 3. THREADS API SETUP

Threads menggunakan Meta Developer Platform yang sama dengan Facebook dan Instagram.

### Step 3.1: Setup Threads API di Meta App

1. **Di Dashboard Meta App (yang sama dengan Facebook/Instagram):**
   - Klik **"Add Product"**
   - Cari **"Threads API"**
   - Klik **"Set Up"**
   - Pilih **"Web"** sebagai platform
   - Klik **"Next"** → **"Skip Quick Start"**

### Step 3.2: Request Permissions

1. **Threads API Permissions:**
   - Klik **"Threads API"** → **"Permissions and Features"**
   - Request permissions:
     - `threads_basic` (Basic Threads access)
     - `threads_content_publish` (Publish to Threads)

2. **Submit for Review:**
   - Request review untuk permissions yang memerlukan approval

### Step 3.3: Dapatkan Access Token

Threads menggunakan access token yang sama dengan Instagram (Page Access Token).

1. **Gunakan Page Access Token yang sama dengan Instagram:**
   - Token yang digunakan untuk Instagram bisa digunakan untuk Threads
   - Atau generate token baru dengan permissions Threads

2. **Generate Token dengan Graph API Explorer:**
   - Buka: https://developers.facebook.com/tools/explorer/
   - Pilih aplikasi Anda
   - Request permissions:
     - `threads_basic`
     - `threads_content_publish`
   - Generate token

### Step 3.4: Dapatkan Threads User ID

1. **Get Threads User ID:**
   ```bash
   curl -X GET "https://graph.threads.net/v1.0/me?\
   fields=id,threads_count&\
   access_token=YOUR_ACCESS_TOKEN"
   ```

2. **Response akan berisi:**
   ```json
   {
     "id": "threads_user_id_here",
     "threads_count": 0
   }
   ```

### Step 3.5: Implementasi di Project

1. **Update `server/.env`:**
   ```env
   # Threads API
   THREADS_ACCESS_TOKEN=your_page_access_token_here
   THREADS_APP_ID=your_app_id_here
   THREADS_USER_ID=your_threads_user_id_here
   ```

2. **Test API:**
   ```bash
   curl http://localhost:5000/api/threads/test
   ```

---

## 🎵 4. TIKTOK API SETUP

### Step 4.1: Buat TikTok Developer Account

1. **Kunjungi TikTok Developers:**
   - Buka: https://developers.tiktok.com/
   - Login dengan akun TikTok Anda
   - Jika belum punya akun developer, daftar dulu

2. **Buat Aplikasi:**
   - Klik **"Create an app"**
   - Isi informasi:
     - **App name:** Nama aplikasi Anda
     - **App description:** Deskripsi aplikasi
     - **App category:** Pilih kategori
     - **App icon:** Upload icon (optional)
   - Klik **"Submit"**

### Step 4.2: Dapatkan Client Key dan Client Secret

1. **Di Dashboard Aplikasi:**
   - Klik **"Basic Information"**
   - Anda akan melihat:
     - **Client Key** - Copy ini
     - **Client Secret** - Klik **"Show"** dan copy (simpan dengan aman!)

### Step 4.3: Setup OAuth Redirect URIs

1. **Di Dashboard:**
   - Klik **"Basic Information"**
   - Scroll ke bagian **"Redirect URI"**
   - Klik **"Add redirect URI"**
   - Tambahkan:
     ```
     http://localhost:5000/api/tiktok/callback
     ```
   - Klik **"Save"**

### Step 4.4: Request API Access

1. **Request Access:**
   - Klik **"Request Access"** atau **"Apply for Access"**
   - Isi form:
     - **Use case:** Jelaskan penggunaan aplikasi
     - **App description:** Deskripsi detail
     - **Website URL:** URL website Anda
   - Submit request

2. **Tunggu Approval:**
   - TikTok akan review aplikasi Anda
   - Proses bisa memakan waktu 1-2 minggu
   - Anda akan mendapat email saat approved

### Step 4.5: Dapatkan Access Token

**Setelah Approved:**

1. **Buat Authorization URL:**
   ```
   https://www.tiktok.com/v2/auth/authorize?
   client_key=YOUR_CLIENT_KEY&
   scope=user.info.basic,video.upload&
   response_type=code&
   redirect_uri=http://localhost:5000/api/tiktok/callback&
   state=random_state_string
   ```

2. **Buka URL di Browser:**
   - Ganti `YOUR_CLIENT_KEY` dengan Client Key Anda
   - Buka URL di browser
   - Login dan authorize aplikasi
   - Anda akan di-redirect ke callback URL dengan `code`

3. **Exchange Code untuk Token:**
   ```bash
   curl -X POST "https://open.tiktokapis.com/v2/oauth/token/" \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "client_key=YOUR_CLIENT_KEY" \
     -d "client_secret=YOUR_CLIENT_SECRET" \
     -d "code=CODE_FROM_CALLBACK" \
     -d "grant_type=authorization_code" \
     -d "redirect_uri=http://localhost:5000/api/tiktok/callback"
   ```

4. **Response akan berisi:**
   ```json
   {
     "access_token": "your_access_token",
     "expires_in": 7200,
     "open_id": "your_open_id",
     "refresh_token": "your_refresh_token",
     "scope": "user.info.basic,video.upload",
     "token_type": "Bearer"
   }
   ```

### Step 4.6: Dapatkan Open ID

Open ID sudah termasuk dalam response saat exchange code untuk token.

1. **Copy `open_id` dari response di atas**

### Step 4.7: Implementasi di Project

1. **Update `server/.env`:**
   ```env
   # TikTok API
   TIKTOK_ACCESS_TOKEN=your_access_token_here
   TIKTOK_CLIENT_KEY=your_client_key_here
   TIKTOK_CLIENT_SECRET=your_client_secret_here
   TIKTOK_OPEN_ID=your_open_id_here
   ```

2. **Test API:**
   ```bash
   curl http://localhost:5000/api/tiktok/test
   ```

---

## 📝 5. IMPLEMENTASI DI PROJECT

### Step 5.1: Update Environment File

1. **Copy file example:**
   ```bash
   cp server/env.example server/.env
   ```

2. **Edit `server/.env` dengan semua credentials:**
   ```env
   # Server Configuration
   PORT=5000
   NODE_ENV=development

   # Facebook API
   FACEBOOK_APP_ID=your_facebook_app_id
   FACEBOOK_APP_SECRET=your_facebook_app_secret
   FACEBOOK_ACCESS_TOKEN=your_facebook_access_token
   FACEBOOK_PAGE_ID=your_facebook_page_id
   FACEBOOK_PAGE_ACCESS_TOKEN=your_facebook_page_access_token

   # Instagram API
   INSTAGRAM_ACCESS_TOKEN=your_instagram_access_token
   INSTAGRAM_USER_ID=your_instagram_user_id
   INSTAGRAM_APP_ID=your_instagram_app_id
   INSTAGRAM_APP_SECRET=your_instagram_app_secret

   # LinkedIn API
   LINKEDIN_ACCESS_TOKEN=your_linkedin_access_token
   LINKEDIN_CLIENT_ID=your_linkedin_client_id
   LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
   LINKEDIN_PERSON_URN=your_linkedin_person_urn

   # Threads API
   THREADS_ACCESS_TOKEN=your_threads_access_token
   THREADS_APP_ID=your_threads_app_id
   THREADS_USER_ID=your_threads_user_id

   # TikTok API
   TIKTOK_ACCESS_TOKEN=your_tiktok_access_token
   TIKTOK_CLIENT_KEY=your_tiktok_client_key
   TIKTOK_CLIENT_SECRET=your_tiktok_client_secret
   TIKTOK_OPEN_ID=your_tiktok_open_id
   ```

### Step 5.2: Buat Route Facebook (Baru)

Buat file `server/routes/facebook.js`:

```javascript
const express = require('express');
const router = express.Router();
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const FACEBOOK_GRAPH_API = 'https://graph.facebook.com';
const FACEBOOK_API_VERSION = 'v18.0';

// Post ke Facebook Page
router.post('/post', async (req, res) => {
  try {
    const { 
      accessToken, 
      pageId, 
      message, 
      imageUrl 
    } = req.body;
    
    if (!accessToken || !pageId || !message) {
      return res.status(400).json({ 
        error: 'accessToken, pageId, dan message diperlukan' 
      });
    }
    
    let postData = {
      message: message,
      access_token: accessToken
    };
    
    // Jika ada gambar, upload dulu
    if (imageUrl) {
      // Upload photo ke Facebook
      const photoResponse = await axios.post(
        `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}/photos`,
        {
          url: imageUrl,
          caption: message,
          access_token: accessToken
        }
      );
      
      res.json({
        success: true,
        postId: photoResponse.data.id,
        message: 'Post berhasil diupload ke Facebook'
      });
    } else {
      // Post text saja
      const postResponse = await axios.post(
        `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}/feed`,
        postData
      );
      
      res.json({
        success: true,
        postId: postResponse.data.id,
        message: 'Post berhasil diupload ke Facebook'
      });
    }
    
  } catch (error) {
    console.error('Facebook API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke Facebook',
      details: error.response?.data || error.message 
    });
  }
});

// Get Facebook Page info
router.get('/page', async (req, res) => {
  try {
    const { accessToken, pageId } = req.query;
    
    if (!accessToken || !pageId) {
      return res.status(400).json({ error: 'accessToken dan pageId diperlukan' });
    }
    
    const response = await axios.get(
      `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}`,
      {
        params: {
          fields: 'id,name,access_token',
          access_token: accessToken
        }
      }
    );
    
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ 
      error: 'Gagal mendapatkan info page',
      details: error.response?.data || error.message 
    });
  }
});

// Test connection
router.get('/test', (req, res) => {
  res.json({ 
    message: 'Facebook API endpoint aktif',
    note: 'Gunakan Facebook Graph API untuk posting. Perlu setup di Facebook Developer Console.'
  });
});

module.exports = router;
```

### Step 5.3: Update Server Index untuk Include Facebook Route

Edit `server/index.js`:

```javascript
// Import routes
const facebookRoutes = require('./routes/facebook');
const instagramRoutes = require('./routes/instagram');
const linkedinRoutes = require('./routes/linkedin');
const threadsRoutes = require('./routes/threads');
const tiktokRoutes = require('./routes/tiktok');
const contentRoutes = require('./routes/content');

// Routes
app.use('/api/facebook', facebookRoutes);
app.use('/api/instagram', instagramRoutes);
app.use('/api/linkedin', linkedinRoutes);
app.use('/api/threads', threadsRoutes);
app.use('/api/tiktok', tiktokRoutes);
app.use('/api/content', contentRoutes);
```

### Step 5.4: Update Frontend untuk Include Facebook

Edit `client/src/components/PlatformSelector.js`:

Tambahkan Facebook ke array platforms:

```javascript
const platforms = [
  { id: 'facebook', name: 'Facebook', icon: FaFacebook, color: '#1877F2' },
  { id: 'instagram', name: 'Instagram', icon: FaInstagram, color: '#E4405F' },
  { id: 'linkedin', name: 'LinkedIn', icon: FaLinkedin, color: '#0077B5' },
  { id: 'threads', name: 'Threads', icon: FaThreads, color: '#000000' },
  { id: 'tiktok', name: 'TikTok', icon: FaTiktok, color: '#000000' }
];
```

Edit `client/src/App.js`:

Tambahkan case untuk Facebook di handlePost:

```javascript
case 'facebook':
  return axios.post('/api/facebook/post', {
    accessToken: localStorage.getItem('facebook_token') || '',
    pageId: localStorage.getItem('facebook_page_id') || '',
    message: content.caption,
    imageUrl: imageUrl
  });
```

### Step 5.5: Test Semua API

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

---

## ✅ Checklist Lengkap

### Facebook & Instagram
- [ ] Facebook Developer Account dibuat
- [ ] Aplikasi Meta dibuat
- [ ] Facebook Login product ditambahkan
- [ ] Instagram Basic Display ditambahkan
- [ ] Instagram Graph API ditambahkan
- [ ] App ID dan App Secret didapatkan
- [ ] OAuth Redirect URIs di-setup
- [ ] Permissions di-request
- [ ] Access Token didapatkan
- [ ] Page ID didapatkan
- [ ] Page Access Token didapatkan
- [ ] Instagram Business Account ID didapatkan
- [ ] Credentials diisi di `.env`
- [ ] Test API berhasil

### LinkedIn
- [ ] LinkedIn Developer Account dibuat
- [ ] Aplikasi LinkedIn dibuat
- [ ] Client ID dan Client Secret didapatkan
- [ ] OAuth Redirect URIs di-setup
- [ ] Permissions di-request
- [ ] Access Token didapatkan
- [ ] Person URN didapatkan
- [ ] Credentials diisi di `.env`
- [ ] Test API berhasil

### Threads
- [ ] Threads API product ditambahkan di Meta App
- [ ] Permissions di-request
- [ ] Access Token didapatkan
- [ ] Threads User ID didapatkan
- [ ] Credentials diisi di `.env`
- [ ] Test API berhasil

### TikTok
- [ ] TikTok Developer Account dibuat
- [ ] Aplikasi TikTok dibuat
- [ ] Client Key dan Client Secret didapatkan
- [ ] OAuth Redirect URIs di-setup
- [ ] API Access di-request
- [ ] Approval diterima
- [ ] Access Token didapatkan
- [ ] Open ID didapatkan
- [ ] Credentials diisi di `.env`
- [ ] Test API berhasil

### Implementasi
- [ ] Facebook route dibuat
- [ ] Server index di-update
- [ ] Frontend di-update untuk include Facebook
- [ ] Environment file diisi lengkap
- [ ] Semua API di-test
- [ ] Posting test berhasil

---

## 🎯 Quick Reference: Semua Credentials

Simpan semua credentials di `server/.env`:

```env
# Facebook
FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_ACCESS_TOKEN=
FACEBOOK_PAGE_ID=
FACEBOOK_PAGE_ACCESS_TOKEN=

# Instagram
INSTAGRAM_ACCESS_TOKEN=
INSTAGRAM_USER_ID=
INSTAGRAM_APP_ID=
INSTAGRAM_APP_SECRET=

# LinkedIn
LINKEDIN_ACCESS_TOKEN=
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_PERSON_URN=

# Threads
THREADS_ACCESS_TOKEN=
THREADS_APP_ID=
THREADS_USER_ID=

# TikTok
TIKTOK_ACCESS_TOKEN=
TIKTOK_CLIENT_KEY=
TIKTOK_CLIENT_SECRET=
TIKTOK_OPEN_ID=
```

---

## ⚠️ Catatan Penting

1. **Token Expiry:**
   - Facebook/Instagram: Long-lived token expired setelah 60 hari
   - LinkedIn: Token expired setelah beberapa bulan
   - TikTok: Token expired setelah beberapa jam
   - Perlu implementasi refresh token

2. **Rate Limiting:**
   - Setiap platform memiliki rate limit
   - Jangan posting terlalu sering

3. **Approval:**
   - Beberapa permissions memerlukan review
   - TikTok memerlukan approval aplikasi
   - Proses bisa memakan waktu

4. **Production:**
   - Jangan commit file `.env`
   - Gunakan environment variables yang aman
   - Setup HTTPS
   - Implementasi error handling yang baik

---

**Selamat! Semua API sudah di-setup dan siap digunakan! 🎉**

