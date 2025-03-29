# SeeQ

# ภาพรวมระบบ

SeeQ เป็นแพลตฟอร์มจองคิวร้านค้า โดยผู้ใช้สามารถค้นหาและจองคิวร้านค้าที่สนใจ พร้อมแสดงสถานะคิวและเปลี่ยนแปลงตามความใกล้ถึงคิว ร้านค้าสามารถเลื่อนคิว บันทึกคิวล่วงหน้า และรวมถึงแสดงสินค้าแนะนำให้ผู้บริโภคได้ ส่วนแอดมินมีสิทธิ์สร้างและจัดการบัญชีร้านค้าและลูกค้า รวมถึงอัปเดตข้อมูลบางส่วนของร้านค้า

# วัตถุประสงค์ของระบบ

- พัฒนาระบบจองคิวร้านค้าที่ช่วยให้ผู้ใช้ค้นหาและจองคิวได้สะดวก พร้อมแสดงสถานะคิวแบบเรียลไทม์
- ให้ร้านค้าสามารถจัดการคิวลูกค้าได้อย่างมีประสิทธิภาพ รวมถึงเลื่อนคิวและบันทึกคิวที่โทรจองล่วงหน้า
- อำนวยความสะดวกให้ผู้ใช้สามารถเข้าถึงร้านค้าผ่านแผนที่ สแกน QR และปรับแต่งข้อมูลส่วนตัวได้
- ให้แอดมินสามารถควบคุมการสร้างบัญชีร้านค้า ดูแลข้อมูลลูกค้า

## Related Repository

- [SeeQ-backend](https://github.com/B1gdawg0/SeeQ-backend.git)
- [SeeQ-Frontend-Customer](https://github.com/ppwlsw/SeeQ-Frontend-Customer)
- [SeeQ-Frontend-Admin](https://github.com/kaepie/SeeQ-Frontend-Admin)
- [SeeQ-Frontend-Shop](https://github.com/ppwlsw/SeeQ-Frontend-Shop)
- [PoS-Stock-Backend](https://github.com/OhmSuphanat/PoS-Stock-Backend)

---

## รายชือสมาชิกและรายวิชาที่ร่วมทำ

| ลำดับ | ชื่อ- นามสกุล | รหัสนิสิต | GitHub Username | ทำร่วมกับรายวิชา | ชั้นปี | หมู่เรียนของ Agile Process and DevOps |
|------|------------|--------|-----------------|---------------|-----|------------------------------------|
| 1 | ศุภณัฐ สร้อยเพชร | 6510450976 | [OhmSuphanat](https://github.com/OhmSuphanat) | Project Management, Intergrated Agile Process and DevOps | 3 | 200 |
| 2 | ปิ่นปวัฒน์ ลิ้มสุวัฒน์ | 6510450658 | [ppwlsw](https://github.com/ppwlsw) | Project Management, Intergrated Agile Process and DevOps | 3 | 200 |
| 3 | เลิศพิพัฒน์ กาญจนเรืองโรจน์ | 6510450917 | [b1gdawg0](https://github.com/B1gdawg0) | Project Management, Intergrated Agile Process and DevOps | 3 | 200 |
| 4 | ชุติพงษ์ ไตรยะสิทธิ์ | 6510450291 | [kaepie](https://github.com/kaepie) | Project Management, Intergrated Agile Process and DevOps | 3 | 202 |
| 5 | ธนวัฒน์ โพธิเดช | 6510450445 | [thanawatptd](https://github.com/thanawatptd) | Project Management, Intergrated Agile Process and DevOps | 3 | 202 |

---

## คำแนะนำในการติดตั้งโครงงานสำหรับการพัฒนาด้วยคำสั่งของ `docker-compose`

หลังจาก clone project มาให้สำรวจ file docker-compose ทำความเข้าใจและปรับ env ตามที่ใช้จริงๆ

---

Env ของ laravel

```bash
APP_NAME=Laravel #ชื่อ app
APP_ENV=local # บอกว่่าเป็น local development
APP_KEY=base64:H3XGG22P78oV3635q13S3AjQK8R/I1vnqSJsNAEOKiA= #คีย์ลับของ Laravel ที่ใช้สำหรับเข้ารหัสข้อมูล
APP_DEBUG=true #เปิด (true) หรือปิด (false) debug mode
APP_TIMEZONE=Asia/Bangkok #กำหนดโซนเวลาของแอป
APP_URL=http://seeq.backend #URL หลักของ Backend แอปพลิเคชัน
CUSTOMER_FRONTEND_URL=http://seeq.customer.frontend #URL ของ Frontend สำหรับลูกค้า
SHOP_FRONTEND_URL=http://seeq.shop.frontend #URL ของ Frontend สำหรับร้านค้า
ADMIN_FRONTEND_URL=http://seeq.admin.frontend #URL ของ Frontend สำหรับผู้ดูแลระบบ
APP_SERVICE=seeq.backend #กำหนดชื่อของ service ในระบบ

APP_LOCALE=en #ภาษาหลักของแอป (en, th, fr ฯลฯ)
APP_FALLBACK_LOCALE=en #ภาษาสำรองในกรณีที่ไม่พบไฟล์แปลของ APP_LOCALE
APP_FAKER_LOCALE=en_US #ภาษาสำหรับ Faker (ใช้ในการสร้างข้อมูลปลอมสำหรับการทดสอบ)

APP_MAINTENANCE_DRIVER=file #ระบุวิธีจัดเก็บสถานะ Maintenance (file หรือ database)
APP_MAINTENANCE_STORE=database #ถ้าใช้ database จะระบุว่าบันทึกข้อมูลใน database
PHP_CLI_SERVER_WORKERS=4 #จำนวน worker process ที่ใช้เมื่อรัน Laravel ผ่าน PHP CLI

BCRYPT_ROUNDS=12 #จำนวนรอบที่ใช้สำหรับการเข้ารหัส bcrypt (ยิ่งมากยิ่งปลอดภัยแต่ใช้เวลาเพิ่มขึ้น)

LOG_CHANNEL=stack #ช่องทางของ log
LOG_STACK=single #วิธีการ stack log
LOG_DEPRECATIONS_CHANNEL=null #ช่องทาง log สำหรับแจ้งเตือน deprecated functions
LOG_LEVEL=debug #ระดับของ log 

DB_CONNECTION=pgsql #ฐานข้อมูลที่ใช้
DB_HOST=seeq.pgsql #Hostname ของฐานข้อมูล
DB_PORT=5432 #พอร์ตของฐานข้อมูล
DB_DATABASE=seeq #ชื่อฐานข้อมูล
DB_USERNAME=admin #ชื่อผู้ใช้ของฐานข้อมูล
DB_PASSWORD=password #รหัสผ่านของฐานข้อมูล

SESSION_DRIVER=database #ระบบที่ใช้จัดเก็บ session
SESSION_LIFETIME=120 #ระยะเวลาหมดอายุของ session (นาที)
SESSION_ENCRYPT=false #กำหนดให้เข้ารหัส session
SESSION_PATH=/ #Path ที่ใช้สำหรับ session cookie
SESSION_DOMAIN=null #ระบุโดเมนที่ session มีผล (ค่า null หมายถึงใช้โดเมนของแอป)

BROADCAST_CONNECTION=log #การเชื่อมต่อสำหรับ broadcasting (log, redis, pusher, ฯลฯ)
FILESYSTEM_DISK=local #วิธีการจัดเก็บไฟล์ (local, s3, public)
QUEUE_CONNECTION=database #การเชื่อมต่อสำหรับ queue (sync, database, redis, ฯลฯ)

CACHE_STORE=database #ระบบที่ใช้เก็บ cache (file, database, redis, ฯลฯ)

MEMCACHED_HOST=127.0.0.1 #ที่อยู่ของเซิร์ฟเวอร์ Memcached

REDIS_CLIENT=phpredis #ชนิดของ Redis client
REDIS_HOST=seeq.redis #Hostname ของ Redis
REDIS_PASSWORD=null #รหัสผ่าน Redis (ค่า null หมายถึงไม่มีรหัสผ่าน)
REDIS_PORT=6379 #พอร์ตของ Redis

MAIL_MAILER=smtp #วิธีการส่งอีเมล 
MAIL_HOST=hmail02.readyidc.cloud #Host ของ SMTP Server
MAIL_PORT=465 #พอร์ต SMTP
MAIL_USERNAME=test@mail.net #ชื่อผู้ใช้ของ SMTP
MAIL_PASSWORD=testsmtp #รหัสผ่าน SMTP
MAIL_ENCRYPTION=tls #วิธีเข้ารหัส (ssl, tls)
MAIL_FROM_ADDRESS="test@mail.net" #อีเมลผู้ส่งเริ่มต้นของระบบ
MAIL_FROM_NAME="TEST" #ชื่อของผู้ส่ง

AWS_ACCESS_KEY_ID=abcde #Access Key ID ที่ใช้สำหรับการเชื่อมต่อ AWS หรือ S3-compatible storage
AWS_SECRET_ACCESS_KEY=abcde #Secret Key ที่ใช้สำหรับยืนยันตัวตนกับ AWS หรือ S3-compatible storage
AWS_DEFAULT_REGION=th #ระบุ region ของ bucket (เช่น us-east-1, ap-southeast-1)
AWS_BUCKET=seetrue #ชื่อของ bucket ที่ใช้สำหรับเก็บไฟล์
AWS_URL=http://server.seeq.com:9000 #URL ของ S3 หรือ MinIO ที่ใช้
AWS_USE_PATH_STYLE_ENDPOINT=true #ใช้ true ถ้าเซิร์ฟเวอร์รองรับ path-style URLs แทนที่จะเป็น virtual-hosted style URLs
AWS_ENDPOINT=http://server.seeq.com:9000 #ใช้สำหรับระบุ endpoint ของ storage ถ้าไม่ได้ใช้ AWS จริงๆ
```

Env ของ frontend customer

```bash
NETWORK_URL = "http://localhost" #ใช้สำหรับยิง API ไปหา Backend ในตอนที่เป็น client side
ENV="local" # บอกว่าเป็น local development
API_BASE_URL="http://seeq.backend/api" # API URL สำหรับ request ไปหา backend ในฝั่ง server side
GOOGLE_CLIENT_ID="123-123.apps.googleusercontent.com" #Client ID ที่ได้จาก Google Cloud Console
GOOGLE_CLIENT_SECRET="111-576-jjj123" #Client Secret ที่ใช้สำหรับยืนยันกับ Google
GOOGLE_CALLBACK_URL="http://localhost:3101/google-callback" # URL ที่ให้ google ส่งข้อมูลกลับมา
```

Env ของ frontend shop และ admin

```bash
ENV="local" # บอกว่าเป็น local developmen
API_BASE_URL="http://seeq.backend/api" # API URL สำหรับ request ไปหา backend ในฝั่ง server side
```

## คำแนะนำการรัน project

### ฝั่ง backend laravel

ใช้คำสั่งด้านล่างใน project laravel

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

หลังจากนั้นใช้คำสั่ง

```bash
sail up -d
sail artisan key:generate
sail artisan migrate:fresh --seed
```

### ฝั่ง frontend ทั้ง customer, shop, admin

```
dokcer compose up -d
```

จากนั้นเข้าไปที่ URL

```
frontend-customer-url: http://localhost:3101/
frontend-shop-url: http://localhost:3102/
frontend-admin-url: http://localhost:3103/
```

## default username และ password สำหรับผู้ใช้แต่ละ role

```python
customer
    username: user1@gmail.com
    password: password
shop
    username: starbuck@gmail.com
    password: password
admin
    username: admin@admin.com
    password: password
```

# Feature รับผิดชอบของแต่ละคน

1. @[OhmSuphanat](https://github.com/OhmSuphanat) Feat: Register
2. @[ppwlsw](https://github.com/ppwlsw) Feat: Map Location
3. @[b1gdawg0](https://github.com/B1gdawg0) Feat: Account
4. @[kaepie](https://github.com/kaepie) Feat: User Management
5. @[OhmSuphanat](https://github.com/OhmSuphanat) Feat: Login
6. @[kaepie](https://github.com/kaepie)  Feat: Reminder
7. @[ppwlsw](https://github.com/ppwlsw) @[kaepie](https://github.com/kaepie)  Feat: Shop Management
8. @[thanawatptd](https://github.com/thanawatptd) Feat: Queue type
9. @[ppwlsw](https://github.com/ppwlsw) @[kaepie](https://github.com/kaepie)  Feat: Dashboard
10. @[thanawatptd](https://github.com/thanawatptd) Feat: Queue Management
11. @[thanawatptd](https://github.com/thanawatptd) Feat: Reserve Queue
12. @[b1gdawg0](https://github.com/B1gdawg0) Feat: Forget Password
13. @[b1gdawg0](https://github.com/B1gdawg0) Feat: Search & Filter
14. @[b1gdawg0](https://github.com/B1gdawg0) Feat: QRcode Scanner
15. @[OhmSuphanat](https://github.com/OhmSuphanat) Feat: Recommend Items
16. @[OhmSuphanat](https://github.com/OhmSuphanat) Feat: Logout

# Release Tag: v1.0.1
