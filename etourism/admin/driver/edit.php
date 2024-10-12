<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `plateNumber`, `fName`, `lName`, و `description` قد تم إرسالهم في الطلب
if (isset($_POST["plateNumber"]) && isset($_POST["fName"]) && isset($_POST["lName"]) && isset($_POST["description"])) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $plateNumber = htmlspecialchars(strip_tags($_POST["plateNumber"]));
    $fName = htmlspecialchars(strip_tags($_POST["fName"]));
    $lName = htmlspecialchars(strip_tags($_POST["lName"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));

    // تحقق من وجود السائق بناءً على `plateNumber`
    $stmtCheck = $con->prepare("SELECT * FROM driver WHERE plateNumber = ?");
    $stmtCheck->execute([$plateNumber]);

    // التحقق مما إذا كان السائق موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // تحديث معلومات السائق
        $stmtUpdate = $con->prepare("UPDATE driver SET fName = ?, lName = ?, description = ? WHERE plateNumber = ?");
        $success = $stmtUpdate->execute([$fName, $lName, $description, $plateNumber]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Driver information updated successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to update driver information.'));
        }
    } else {
        // إذا لم يتم العثور على السائق، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Driver not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Plate number, first name, last name, and description are required.'));
}
?>
