<?php
// ส่วนประมวลผลการส่งอีเมล (PHP)
$status_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบ Captcha บวกลข (ป้องกัน Spam Bot)
    $captcha_answer = $_POST['captcha_answer'];
    
    if ($captcha_answer == "8") { // คำตอบของ 5 + 3
        $name = strip_tags(trim($_POST["name"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $subject = strip_tags(trim($_POST["subject"]));
        $message = trim($_POST["message"]);

        // ตั้งค่าอีเมลปลายทาง (อ้างอิงจากเว็บเก่า)
        $to = "topsleep@gmail.com"; 
        
        // หัวข้ออีเมล
        $email_subject = "ร้องเรียน/แนะนำ จากหน้าเว็บไซต์: $subject";
        
        // เนื้อหาอีเมล
        $email_content = "มีข้อความใหม่ส่งมาจากหน้าเว็บไซต์โรงพยาบาลปากท่อ\n\n";
        $email_content .= "ชื่อผู้ติดต่อ: $name\n";
        $email_content .= "อีเมลสำหรับติดต่อกลับ: $email\n\n";
        $email_content .= "หัวข้อเรื่อง: $subject\n";
        $email_content .= "รายละเอียดข้อความ:\n$message\n";

        // ตั้งค่า Header (ให้รองรับภาษาไทย)
        $headers = "From: webmaster@pthosp.net\r\n"; // ควรเปลี่ยนเป็นอีเมลระบบของเซิร์ฟเวอร์คุณ
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // สั่งส่งอีเมล
        if (mail($to, $email_subject, $email_content, $headers)) {
            $status_msg = "<div class='alert success'><i class='fa-solid fa-circle-check'></i> ส่งข้อความของท่านเรียบร้อยแล้ว เจ้าหน้าที่จะดำเนินการตรวจสอบโดยเร็วที่สุด ขอบคุณครับ/ค่ะ</div>";
        } else {
            $status_msg = "<div class='alert error'><i class='fa-solid fa-circle-xmark'></i> ขออภัย ระบบไม่สามารถส่งข้อความได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง หรือติดต่อทางโทรศัพท์</div>";
        }
    } else {
        $status_msg = "<div class='alert error'><i class='fa-solid fa-triangle-exclamation'></i> รหัสยืนยันความปลอดภัย (ผลบวกตัวเลข) ไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แนะนำ-ติชม-ร้องเรียน - โรงพยาบาลปากท่อ</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-pink: #D81B60;
            --accent-pink: #F06292;
            --light-pink: #FCE4EC;
            --bg-pink: #FFF5F8;
            --gray-text: #555;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Prompt', sans-serif; }
        body { background-color: var(--bg-pink); color: #333; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; } 

        /* Header */
        header { background: #fff; box-shadow: 0 2px 15px rgba(216, 27, 96, 0.08); position: sticky; top: 0; z-index: 100; }
        .header-main { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { display: flex; align-items: center; gap: 15px; font-size: 24px; font-weight: 700; color: var(--primary-pink); text-decoration: none; }
        .btn-home { background: var(--light-pink); color: var(--primary-pink); font-weight: 600; text-decoration: none; padding: 10px 15px; border-radius: 6px; transition: 0.3s; }
        .btn-home:hover { background: var(--primary-pink); color: #fff; }

        /* Page Header */
        .page-header { text-align: center; padding: 40px 0 30px; }
        .page-header h1 { font-size: 32px; color: var(--primary-pink); margin-bottom: 5px; }
        .page-header p { color: var(--gray-text); font-size: 16px; }

        /* การแจ้งเตือนเมื่อกดส่งฟอร์ม */
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; font-size: 16px; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Layout แบ่ง 2 ฝั่ง ซ้ายเบอร์โทร - ขวาฟอร์มอีเมล */
        .contact-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; padding-bottom: 60px; align-items: start; }

        .content-card { background: #fff; border-radius: 15px; padding: 35px; box-shadow: 0 5px 20px rgba(216, 27, 96, 0.05); border: 1px solid var(--light-pink); height: 100%; }
        .content-card h2 { font-size: 22px; color: var(--primary-pink); border-bottom: 2px solid var(--light-pink); padding-bottom: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }

        /* รายชื่อเบอร์โทร */
        .complaint-list { display: flex; flex-direction: column; gap: 12px; }
        .complaint-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: #fafafa; border-radius: 8px; border: 1px solid #eee; transition: 0.2s; }
        .complaint-item:hover { border-color: var(--accent-pink); background: #fff; transform: translateX(5px); box-shadow: 0 2px 10px rgba(216, 27, 96, 0.05); }
        .complaint-name { font-weight: 500; color: #444; font-size: 16px; display: flex; align-items: center; gap: 10px;}
        .complaint-name i { color: var(--accent-pink); }
        .complaint-phone { background: var(--bg-pink); color: var(--primary-pink); padding: 6px 15px; border-radius: 20px; font-size: 15px; font-weight: 600; text-decoration: none; border: 1px solid var(--light-pink); transition: 0.3s; }
        .complaint-phone:hover { background: var(--primary-pink); color: #fff; }

        /* แบบฟอร์มติดต่อ */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; color: #444; margin-bottom: 8px; font-size: 15px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Prompt', sans-serif; font-size: 15px; background: #fafafa; transition: 0.3s; }
        .form-control:focus { outline: none; border-color: var(--primary-pink); background: #fff; box-shadow: 0 0 0 3px rgba(216, 27, 96, 0.1); }
        textarea.form-control { resize: vertical; min-height: 120px; }
        
        .captcha-box { display: flex; align-items: center; justify-content: space-between; background: #fff5f8; padding: 15px; border-radius: 8px; border: 1px dashed var(--accent-pink); margin-bottom: 20px; }
        .captcha-question { font-size: 18px; font-weight: 700; color: var(--primary-pink); letter-spacing: 2px; }
        
        .btn-submit { background: var(--primary-pink); color: #fff; border: none; padding: 15px; font-size: 16px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: 0.3s; width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; font-family: 'Prompt', sans-serif; }
        .btn-submit:hover { background: #b0124a; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(216, 27, 96, 0.2); }

        footer { background: #3c1020; color: #fce4ec; padding: 30px 0; text-align: center; margin-top: auto; font-size: 14px; }

        @media (max-width: 900px) {
            .contact-layout { grid-template-columns: 1fr; gap: 20px; }
            .header-main { flex-direction: column; }
        }
    </style>
</head>
<body>

    <header>
        <div class="container header-main">
            <a href="index.html" class="logo">
                <img src="Picture/logo.png" alt="โลโก้" style="height: 50px;">
                โรงพยาบาลปากท่อ
            </a>
            <a href="index.html" class="btn-home"><i class="fa-solid fa-house"></i> กลับหน้าแรก</a>
        </div>
    </header>

    <div class="container page-header">
        <h1><i class="fa-solid fa-comments"></i> แนะนำ / ติชม / ร้องเรียน</h1>
        <p>ช่องทางการติดต่อและรับฟังความคิดเห็น โรงพยาบาลปากท่อ</p>
    </div>

    <div class="container">
        <!-- แสดงข้อความสถานะเมื่อกดส่งอีเมล -->
        <?php echo $status_msg; ?>

        <div class="contact-layout">
            
            <!-- ฝั่งซ้าย: รายชื่อเบอร์โทรศัพท์ (อิงรายชื่อจากตารางเก่า) -->
            <div class="content-card">
                <h2><i class="fa-solid fa-phone-volume"></i> ติดต่อทางโทรศัพท์</h2>
                <div class="complaint-list">
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user-tie"></i> คุณปิยารัตน์ ทองย้อย</span>
                        <a href="tel:0814343824" class="complaint-phone">081-4343824</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณเพียงเพ็ญ สุขตมสันติ</span>
                        <a href="tel:0818559288" class="complaint-phone">081-8559288</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณวาสนา จันทร์เพ็ญ</span>
                        <a href="tel:0871672155" class="complaint-phone">087-1672155</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณนุชสรา เยื่อปุย</span>
                        <a href="tel:0870951507" class="complaint-phone">087-0951507</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณรดา สุทธาวาศ</span>
                        <a href="tel:0970297612" class="complaint-phone">097-0297612</a>
                    </div>
                    <div class="contact-item" style="padding:12px 15px; border-bottom:1px solid #eee; display:flex; justify-content:space-between;">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณจุฑาทิพย์ จ่าปาหอม</span>
                        <a href="tel:0899186968" class="complaint-phone">089-9186968</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณเจริญใจ ชื่นบาน</span>
                        <a href="tel:0811987038" class="complaint-phone">081-1987038</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณณัฎฐวุฒิ ช้างป่าดี</span>
                        <a href="tel:0910182328" class="complaint-phone">091-0182328</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณนภาวรรณ น่วมด้วง</span>
                        <a href="tel:0815703562" class="complaint-phone">081-5703562</a>
                    </div>
                    <div class="complaint-item">
                        <span class="complaint-name"><i class="fa-solid fa-user"></i> คุณนลินรัตน์ ศรีสนธิรักษ์</span>
                        <a href="tel:0945423224" class="complaint-phone">094-5423224</a>
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: แบบฟอร์มส่งอีเมล (ทำงานได้จริงผ่าน PHP) -->
            <div class="content-card">
                <h2><i class="fa-solid fa-envelope-open-text"></i> ติดต่อทางอีเมล</h2>
                <p style="color: #666; margin-bottom: 20px; font-size: 14px;">
                    กรุณากรอกข้อมูลด้านล่าง ข้อความของท่านจะถูกส่งตรงไปยังอีเมล <strong style="color:var(--primary-pink);">topsleep@gmail.com</strong>
                </p>

                <!-- สังเกต method="POST" คือการส่งข้อมูลไปให้ PHP ด้านบนประมวลผล -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="name">ชื่อ - นามสกุล ผู้ติดต่อ <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="กรอกชื่อของคุณ" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">อีเมลสำหรับติดต่อกลับ <span style="color:red;">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">หัวข้อเรื่อง <span style="color:red;">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="ระบุหัวข้อเรื่องที่ต้องการร้องเรียนหรือแนะนำ" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">รายละเอียดข้อความ <span style="color:red;">*</span></label>
                        <textarea id="message" name="message" class="form-control" placeholder="กรอกรายละเอียดข้อความของท่านที่นี่..." required></textarea>
                    </div>

                    <!-- กล่องยืนยันตัวตนแบบบวกเลข (ใช้ง่ายกว่าการพิมพ์ตัวอักษรภาษาอังกฤษ) -->
                    <div class="captcha-box">
                        <div>
                            <label style="margin-bottom: 0;">รหัสยืนยันความปลอดภัย <span style="color:red;">*</span></label>
                            <span style="font-size: 13px; color: #777;">กรุณาหาผลบวกของตัวเลขนี้</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span class="captcha-question">5 + 3 = </span>
                            <input type="number" name="captcha_answer" class="form-control" style="width: 80px; text-align: center; padding: 10px;" placeholder="?" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-regular fa-paper-plane"></i> ส่งข้อความ (Send Message)
                    </button>
                </form>
            </div>

        </div>
    </div>

    <footer>
        <div class="container">
            <p>โรงพยาบาลปากท่อ เลขที่ 201/10 หมู่ 8 ถ.ท้าวอู่ทอง ต.ปากท่อ อ.ปากท่อ จ.ราชบุรี 70140</p>
            <p style="color: #f8bbd0; margin-top: 5px;">Copyright 2026 by Paktho Computer Center.</p>
        </div>
    </footer>

</body>
</html>