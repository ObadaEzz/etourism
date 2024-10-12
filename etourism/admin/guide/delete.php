<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `mobile` قد تم إرساله في الطلب
if (isset($_POST["mobile"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $mobile = htmlspecialchars(strip_tags($_POST["mobile"]));

    // تحقق من وجود المرشد بناءً على `mobile`
    $stmtCheck = $con->prepare("SELECT * FROM guide WHERE mobile = ?");
    $stmtCheck->execute([$mobile]);

    // التحقق مما إذا كان المرشد موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // إذا كان المرشد موجودًا، نقوم بحذفه
        $stmtDelete = $con->prepare("DELETE FROM guide WHERE mobile = ?");
        $success = $stmtDelete->execute([$mobile]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Guide deleted successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to delete guide.'));
        }
    } else {
        // إذا لم يتم العثور على المرشد، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Guide not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Mobile number is required.'));
}
?>
