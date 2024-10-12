<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `programme_id` قد تم إرساله في الطلب
if (isset($_POST["programme_id"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $programme_id = htmlspecialchars(strip_tags($_POST["programme_id"]));

    // تحقق من وجود البرنامج بناءً على `programme_id`
    $stmtCheck = $con->prepare("SELECT * FROM programme WHERE programme_id = ?");
    $stmtCheck->execute([$programme_id]);

    // التحقق مما إذا كان البرنامج موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // إذا كان البرنامج موجودًا، نقوم بحذفه
        $stmtDelete = $con->prepare("DELETE FROM programme WHERE programme_id = ?");
        $success = $stmtDelete->execute([$programme_id]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Programme deleted successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to delete programme.'));
        }
    } else {
        // إذا لم يتم العثور على البرنامج، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Programme not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Programme ID is required.'));
}
?>
