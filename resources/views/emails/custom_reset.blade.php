<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>รีเซ็ตรหัสผ่าน</title>
    <style>
        body {
            font-family: 'Prompt', 'Sarabun', sans-serif;
            line-height: 1.6;
            color: #334155;
            margin: 0;
            padding: 20px;
            background-color: #f1f5f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideIn 0.8s ease-out;
        }

        .header {
            background: linear-gradient(135deg, #242F40, #0284c7);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .message {
            text-align: center;
            font-size: 16px;
            color: #475569;
            margin-bottom: 30px;
        }

        .reset-button {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 30px auto;
            padding: 15px 25px;
            background: linear-gradient(135deg, #1ca14d, #16a34a);
            color: white !important;
            text-decoration: none;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .reset-button:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .reset-link {
            margin-top: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 14px;
            color: #64748b;
            word-break: break-all;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>รีเซ็ตรหัสผ่านของคุณ</h1>
    </div>
    <div class="content">
        <div class="message">
            <p>เรียนคุณ
                @if ($role === 'ADMIN')
                    Administrator
                @elseif ($role === 'SHOP')
                    Shop Owner
                @else
                    Customer
                @endif
                ,</p>
            <p>คุณได้ทำการร้องขอรีเซ็ตรหัสผ่าน กรุณาคลิกลิงก์ด้านล่างเพื่อดำเนินการ</p>
        </div>

        @php
            $resetUrl = match ($role) {
                'admin' => env('ADMIN_FRONTEND_URL'),
                'shop' => env('SHOP_FRONTEND_URL'),
                default => env('CUSTOMER_FRONTEND_URL')
            };
        @endphp

        <a href="{{ $resetUrl }}/reset-password?token={{ $token }}" class="reset-button">
            รีเซ็ตรหัสผ่าน
        </a>

        <div class="reset-link">
            <strong>หรือคัดลอกลิงก์นี้ไปวางในเบราว์เซอร์ของคุณ:</strong><br>
            {{ $resetUrl }}/reset-password?token={{ $token }}
        </div>

        <div class="footer">
            <p>หากคุณไม่ได้ทำการร้องขอนี้ กรุณาละเว้นอีเมลนี้<br>
                ขอบคุณ,<br>
                ทีมงานของเรา</p>
        </div>
    </div>
</div>
</body>
</html>
