<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `programme_id`, `name`, `description`, `start_date`, `end_date`, و `price` قد تم إرسالهم في الطلب
if (
    isset($_POST["programme_id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["description"]) &&
    isset($_POST["start_date"]) &&
    isset($_POST["end_date"]) &&
    isset($_POST["price"])
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $programme_id = htmlspecialchars(strip_tags($_POST["programme_id"]));
    $name = htmlspecialchars(strip_tags($_POST["name"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));
    $start_date = htmlspecialchars(strip_tags($_POST["start_date"]));
    $end_date = htmlspecialchars(strip_tags($_POST["end_date"]));
    $price = htmlspecialchars(strip_tags($_POST["price"]));

    // تحقق من وجود البرنامج بناءً على `programme_id`
    $stmtCheck = $con->prepare("SELECT * FROM programme WHERE programme_id = ?");
    $stmtCheck->execute([$programme_id]);

    // التحقق مما إذا كان البرنامج موجودًا
    if ($stmtCheck->rowCount() > 0) {
        // تحديث معلومات البرنامج
        $stmtUpdate = $con->prepare("UPDATE programme SET name = ?, description = ?, start_date = ?, end_date = ?, price = ? WHERE programme_id = ?");
        $success = $stmtUpdate->execute([$name, $description, $start_date, $end_date, $price, $programme_id]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Programme information updated successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to update programme information.'));
        }
    } else {
        // إذا لم يتم العثور على البرنامج، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Programme not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Programme ID, name, description, start date, end date, and price are required.'));
}
?>
