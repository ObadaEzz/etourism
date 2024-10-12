<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `mobile`, `fName`, `lName`, و `description` قد تم إرسالهم في الطلب
if (isset($_POST["mobile"]) && isset($_POST["fName"]) && isset($_POST["lName"]) && isset($_POST["description"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $mobile = htmlspecialchars(strip_tags($_POST["mobile"]));
    $fName = htmlspecialchars(strip_tags($_POST["fName"]));
    $lName = htmlspecialchars(strip_tags($_POST["lName"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));

    // تحقق من وجود المرشد بناءً على `mobile`
    $stmtCheck = $con->prepare("SELECT * FROM guide WHERE mobile = ?");
    $stmtCheck->execute([$mobile]);

    // التحقق مما إذا كان المرشد موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // تحديث معلومات المرشد
        $stmtUpdate = $con->prepare("UPDATE guide SET fName = ?, lName = ?, description = ? WHERE mobile = ?");
        $success = $stmtUpdate->execute([$fName, $lName, $description, $mobile]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Guide information updated successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to update guide information.'));
        }
    } else {
        // إذا لم يتم العثور على المرشد، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Guide not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Mobile number, first name, last name, and description are required.'));
}
?>
