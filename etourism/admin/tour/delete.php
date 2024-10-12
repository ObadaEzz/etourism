<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `tour_id` قد تم إرساله في الطلب
if (isset($_POST["tour_id"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $tour_id = htmlspecialchars(strip_tags($_POST["tour_id"]));

    // تحقق من وجود الرحلة بناءً على `tour_id`
    $stmtCheck = $con->prepare("SELECT * FROM tour WHERE tour_id = ?");
    $stmtCheck->execute([$tour_id]);

    // التحقق مما إذا كانت الرحلة موجودة
    if ($stmtCheck->rowCount() > 0) {
        // إذا كانت الرحلة موجودة، نقوم بحذفها
        $stmtDelete = $con->prepare("DELETE FROM tour WHERE tour_id = ?");
        $success = $stmtDelete->execute([$tour_id]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Tour deleted successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to delete tour.'));
        }
    } else {
        // إذا لم يتم العثور على الرحلة، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Tour not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Tour ID is required.'));
}
?>
