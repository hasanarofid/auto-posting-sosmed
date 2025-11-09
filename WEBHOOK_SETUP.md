# 🔧 Setup Facebook Webhook

Panduan untuk memperbaiki error "URL callback atau token verifikasi tidak dapat divalidasi".

## ❌ Masalah

Error: "URL callback atau token verifikasi tidak dapat divalidasi"

Ini terjadi karena:
1. Endpoint webhook belum ada di backend
2. Endpoint tidak merespons dengan benar untuk verifikasi Facebook

## ✅ Solusi

### Step 1: Pastikan File Webhook Sudah Ada

File `routes/webhook.php` sudah dibuat dan di-copy ke folder `deploy/api/routes/`.

### Step 2: Upload File ke cPanel

1. **Via FileZilla:**
   - Upload file `deploy/api/routes/webhook.php` ke:
     ```
     public_html/sosmed/api/routes/webhook.php
     ```

2. **Via cPanel File Manager:**
   - Masuk ke `public_html/sosmed/api/routes/`
   - Upload file `webhook.php`

### Step 3: Update Config File

1. Di cPanel File Manager, masuk ke `public_html/sosmed/api/`
2. Edit file `config.local.php`
3. Tambahkan verification token:

```php
define('FACEBOOK_WEBHOOK_VERIFY_TOKEN', 'sosmed_webhook_verification_2024');
```

**Pastikan token ini sama dengan yang diisi di Facebook Developer!**

### Step 4: Test Endpoint

1. Buka browser dan test endpoint:
   ```
   https://sosmed.solusicodekata.com/api/webhook?hub.mode=subscribe&hub.verify_token=sosmed_webhook_verification_2024&hub.challenge=test123
   ```

2. Harus return: `test123` (challenge value)

### Step 5: Verifikasi di Facebook Developer

1. Kembali ke halaman Webhooks di Facebook Developer
2. Pastikan:
   - **URL Callback:** `https://sosmed.solusicodekata.com/api/webhook`
   - **Verifikasi token:** `sosmed_webhook_verification_2024`
3. Klik **"Verifikasi dan simpan"** (Verify and save)

## 📝 Catatan Penting

### URL Callback yang Benar

**Untuk Webhook:**
```
https://sosmed.solusicodekata.com/api/webhook
```

**Bukan:**
```
https://sosmed.solusicodekata.com/api/facebook/callback
```

### Verification Token

Token harus sama di:
1. Facebook Developer (field "Verifikasi token")
2. File `config.local.php` (FACEBOOK_WEBHOOK_VERIFY_TOKEN)

### Endpoint Webhook

Endpoint webhook berbeda dengan OAuth callback:
- **OAuth Callback:** `/api/facebook/callback` (untuk OAuth flow)
- **Webhook:** `/api/webhook` (untuk webhook events)

## 🔍 Troubleshooting

### Problem: "URL callback tidak dapat divalidasi"

**Solusi:**
1. Pastikan file `webhook.php` sudah di-upload
2. Pastikan endpoint bisa diakses: `https://sosmed.solusicodekata.com/api/webhook`
3. Test endpoint dengan URL di Step 4
4. Pastikan token sama di Facebook dan config

### Problem: "Token verifikasi tidak cocok"

**Solusi:**
1. Pastikan token di Facebook Developer sama dengan di `config.local.php`
2. Pastikan tidak ada spasi atau karakter tambahan
3. Pastikan file `config.local.php` sudah di-update

### Problem: "404 Not Found"

**Solusi:**
1. Pastikan file `webhook.php` ada di `api/routes/`
2. Pastikan routing di `index.php` sudah benar
3. Pastikan `.htaccess` sudah benar

## ✅ Checklist

- [ ] File `webhook.php` sudah di-upload ke `api/routes/`
- [ ] File `config.local.php` sudah di-update dengan `FACEBOOK_WEBHOOK_VERIFY_TOKEN`
- [ ] Token di Facebook Developer sama dengan di config
- [ ] URL Callback di Facebook: `https://sosmed.solusicodekata.com/api/webhook`
- [ ] Test endpoint berhasil (return challenge)
- [ ] Klik "Verifikasi dan simpan" di Facebook Developer

## 🎯 Quick Fix

1. **Upload file webhook:**
   ```
   deploy/api/routes/webhook.php → public_html/sosmed/api/routes/webhook.php
   ```

2. **Update config:**
   ```php
   define('FACEBOOK_WEBHOOK_VERIFY_TOKEN', 'sosmed_webhook_verification_2024');
   ```

3. **Update URL di Facebook:**
   ```
   https://sosmed.solusicodekata.com/api/webhook
   ```

4. **Klik "Verifikasi dan simpan"**

---

**Setelah ini, webhook akan terverifikasi! ✅**

