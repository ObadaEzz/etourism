<?php
// استيراد ملف الاتصال بقاعدة البيانات
include 'db_connection.php';

// فحص ما إذا كان الطلب POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // الحصول على البريد الإلكتروني المرسل من التطبيق
    $email = $_POST['email'];

    // التأكد من أن الحقل غير فارغ
    if (!empty($email)) {
        // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            /* // إذا كان البريد الإلكتروني موجودًا، أنشئ رمز إعادة تعيين
            $token = bin2hex(random_bytes(50)); // إنشاء رمز عشوائي

            // حفظ الرمز في قاعدة البيانات مع الوقت الحالي
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();

            // إعداد رسالة البريد الإلكتروني
            $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;
            $subject = "Password Reset Request";
            $message = "You requested a password reset. Click the link below to reset your password:\n\n";
            $message .= $resetLink . "\n\nThis link will expire in 1 hour.";
            $headers = "From: noreply@yourdomain.com";
 */
            // إرسال البريد الإلكتروني
            if (mail($email, $subject, $message, $headers)) {
                $response = [
                    'status' => 'success',
                    'message' => 'A password reset link has been sent to your email.'
                ];
            } /* else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to send email. Please try again.'
                ];
            } */
        } else {
            // إذا لم يتم العثور على البريد الإلكتروني
            $response = [
                'status' => 'error',
                'message' => 'No account found with that email.'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Please provide your email address.'
        ];
    }

    // إرسال الاستجابة بتنسيق JSON إلى التطبيق
    echo json_encode($response);
}
?>
