# 🚀 CMS Project Setup Guide

## आवश्यक सॉफ्टवेयर:

### 1. XAMPP Install करें
1. **Download करें**: https://www.apachefriends.org/download.html
2. **Install करें** (सभी components select करें)
3. **Install Location**: `C:\xampp` (default)

---

## 📦 Setup Steps:

### Step 1: XAMPP Install करें
- XAMPP download करके install करें
- Installation के दौरान **Apache** और **MySQL** दोनों select करें

### Step 2: प्रोजेक्ट को Copy करें
1. अपने `cms` folder को copy करें
2. इसे यहाँ paste करें: `C:\xampp\htdocs\`
3. Final path होगा: `C:\xampp\htdocs\cms\`

### Step 3: Database Setup

#### Option A: अगर आपके पास Database Backup है
1. XAMPP Control Panel खोलें
2. **Apache** और **MySQL** को Start करें
3. Browser में जाएं: `http://localhost/phpmyadmin`
4. Left sidebar में **New** button click करें
5. Database name डालें: `u615712904_a2p`
6. **Create** button click करें
7. ऊपर **Import** tab पर click करें
8. **Choose File** से अपनी `.sql` file select करें
9. **Go** button click करें

#### Option B: अगर Database Local में है
1. `function/function.php` file खोलें
2. Lines 11-13 में database credentials update करें:
```php
$dsn = 'mysql:host=localhost;dbname=u615712904_a2p;charset=utf8mb4';
$user = 'root';  // XAMPP में default username 'root' होता है
$pass = '';      // XAMPP में default password खाली होता है
```

### Step 4: Project को Run करें
1. XAMPP Control Panel में **Apache** और **MySQL** दोनों Start करें
2. Browser खोलें
3. इस URL पर जाएं: `http://localhost/cms/`

---

## 🔧 Troubleshooting:

### Problem 1: Port 80 already in use
**Solution:**
1. XAMPP Control Panel में Apache के सामने **Config** button click करें
2. **httpd.conf** select करें
3. `Listen 80` को `Listen 8080` में बदलें
4. `ServerName localhost:80` को `ServerName localhost:8080` में बदलें
5. File save करें और Apache restart करें
6. अब browser में जाएं: `http://localhost:8080/cms/`

### Problem 2: MySQL Port 3306 already in use
**Solution:**
1. XAMPP Control Panel में MySQL के सामने **Config** button click करें
2. **my.ini** select करें
3. `port=3306` को `port=3307` में बदलें
4. File save करें और MySQL restart करें

### Problem 3: Database Connection Error
**Solution:**
1. Check करें कि MySQL running है
2. `function/function.php` में database credentials verify करें
3. phpMyAdmin में check करें कि database exist करता है

### Problem 4: Blank Page या Errors
**Solution:**
1. `function/function.php` में line 2 को बदलें:
```php
error_reporting(E_ALL);  // सभी errors देखने के लिए
```
2. Browser console में errors check करें

---

## 📱 Admin Panel Access:

अगर आपके प्रोजेक्ट में admin panel है:
- URL: `http://localhost/cms/admin/`
- या: `http://localhost/cms/superadmin/`

---

## 🌐 Live Site URL को Local में बदलना:

आपके code में live URL है: `https://a2prealtech.com/`

**Local development के लिए:**
1. `function/function.php` खोलें
2. Line 6 को बदलें:
```php
// Production
// define('SITE_URL', 'https://a2prealtech.com/');

// Local Development
define('SITE_URL', 'http://localhost/cms/');
```

---

## ✅ Verification Checklist:

- [ ] XAMPP installed
- [ ] Apache running (green indicator)
- [ ] MySQL running (green indicator)
- [ ] Project copied to `C:\xampp\htdocs\cms\`
- [ ] Database created in phpMyAdmin
- [ ] Database credentials updated in `function/function.php`
- [ ] SITE_URL updated to local URL
- [ ] Browser में `http://localhost/cms/` खुल रहा है

---

## 🎯 Quick Start Commands:

### XAMPP को Command Line से Start करें:
```cmd
cd C:\xampp
xampp-control.exe
```

### Database Backup लेना:
1. phpMyAdmin खोलें: `http://localhost/phpmyadmin`
2. Database select करें: `u615712904_a2p`
3. ऊपर **Export** tab click करें
4. **Go** button click करें
5. `.sql` file download हो जाएगी

---

## 📞 Need Help?

अगर कोई problem आए तो:
1. XAMPP error logs check करें: `C:\xampp\apache\logs\error.log`
2. PHP errors enable करें (ऊपर देखें)
3. Browser console में errors check करें

---

## 🔥 Pro Tips:

1. **हमेशा backup लें** database और files का
2. **Local development** के लिए SITE_URL को local में रखें
3. **Error reporting** development में ON रखें
4. **Production** में जाने से पहले सभी errors fix करें
5. **Git** use करें version control के लिए

---

**Happy Coding! 🚀**
