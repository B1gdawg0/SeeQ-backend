<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ยืนยันบัญชีร้านค้า</title>
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
            overflow: hidden;
        }

        .header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite linear;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            color: #475569;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
        }

        .details-table tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.3s ease;
        }

        .details-table tr:hover {
            background-color: #f1f5f9;
        }

        .details-table tr:last-child {
            border-bottom: none;
        }

        .details-table td {
            padding: 12px 15px;
        }

        .details-table td:first-child {
            width: 35%;
            font-weight: 600;
            color: #1e293b;
        }

        .verify-button {
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

        .verify-button:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .verification-link {
            margin-top: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 14px;
            color: #64748b;
            word-break: break-all;
            transition: background-color 0.3s ease;
        }

        .verification-link:hover {
            background: #e2e8f0;
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
        <h1>ยินดีต้อนรับสู่ระบบร้านค้า</h1>
    </div>
    <div class="content">
        <div class="welcome-text">
            <p>สวัสดีคุณ {{ $shop->name }},<br>
                ขอบคุณที่ลงทะเบียนร้านค้ากับเรา กรุณาตรวจสอบข้อมูลและยืนยันบัญชีของคุณ</p>
        </div>

        <table class="details-table">
            <tr>
                <td>ชื่อร้าน</td>
                <td>{{ $shop->name }}</td>
            </tr>
            <tr>
                <td>อีเมล</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>ที่อยู่</td>
                <td>{{ $shop->address ?? 'ไม่ระบุ' }}</td>
            </tr>
            <tr>
                <td>เบอร์โทรศัพท์</td>
                <td>{{ $shop->phone ?? 'ไม่ระบุ' }}</td>
            </tr>
            <tr>
                <td>รายละเอียด</td>
                <td>{{ $shop->description ?? 'ไม่ระบุ' }}</td>
            </tr>
        </table>

        <a href="{{ $verificationLink }}" class="verify-button">
            ยืนยันบัญชีของคุณ
        </a>

        <div class="verification-link">
            <strong>หรือคัดลอกลิงก์นี้ไปวางในเบราว์เซอร์:</strong><br>
            {{ $verificationLink }}
        </div>

        <div class="footer">
            <p>หากคุณไม่ได้ทำการลงทะเบียน กรุณาละเว้นข้อความนี้<br>
                ขอบคุณ,<br>
                ทีมงานของเรา</p>
        </div>
    </div>
</div>
</body>
</html>
