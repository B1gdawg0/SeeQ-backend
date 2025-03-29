<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันบัญชีสำเร็จ</title>
    <style>
        /* Animation สำหรับการแสดงข้อความ */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* สไตล์ของข้อความ */
        .confirmation-message {
            text-align: center;
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 30px;
            max-width: 400px;
            margin: 50px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-out;
        }

        /* สไตล์ของหัวข้อ */

        @if($status === 'success')
            .confirmation-message h1 {
                color: #38a169;
                font-size: 1.5rem;
                font-weight: bold;
            }
        @else
            .confirmation-message h1 {
                color: #e53e3e;
                font-size: 1.5rem;
                font-weight: bold;
            }
        @endif

        /* สไตล์ของข้อความเนื้อหา */
        .confirmation-message p {
            color: #4a5568;
            margin-top: 10px;
        }

        /* ปุ่มเพื่อทำการแสดงผลข้อความ */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3182ce;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2b6cb0;
        }
    </style>
</head>
<body>
<div class="confirmation-message">
    @if($status === 'success')
        <h1>ยืนยันบัญชีสำเร็จ</h1>
        <p>เริ่มใช้บริการจากแอพพลิเคชั่น SeeQ ได้เลย!</p>
        <a href={{ $path_link }} class="btn">กลับไปที่หน้าแรก</a>
    @else
        <h1>การยืนยันบัญชีไม่สำเร็จ</h1>
        <p>หากเคยยืนยันบัญชีแล้ว สามารถเข้าสู่ระบบได้เลย</p>
        <p>หรือหากการยืนยันบัญชีไม่สำเร็จ กรุณาลองใหม่อีกครั้งหรือ</p>
        <p>ติดต่อเจ้าหน้าที่เพื่อทำการยืนยันบัญชีใหม่อีกครั้ง</p>
    @endif
</div>
</body>
</html>
