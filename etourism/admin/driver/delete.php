<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `plateNumber` قد تم إرساله في الطلب
if (isset($_POST["plateNumber"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $plateNumber = htmlspecialchars(strip_tags($_POST["plateNumber"]));

    // تحقق من وجود السائق بناءً على `plateNumber`
    $stmtCheck = $con->prepare("SELECT * FROM driver WHERE plateNumber = ?");
    $stmtCheck->execute([$plateNumber]);

    // التحقق مما إذا كان السائق موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // إذا كان السائق موجودًا، نقوم بحذفه
        $stmtDelete = $con->prepare("DELETE FROM driver WHERE plateNumber = ?");
        $success = $stmtDelete->execute([$plateNumber]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Driver deleted successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to delete driver.'));
        }
    } else {
        // إذا لم يتم العثور على السائق، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Driver not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Plate number is required.'));
}
?>
