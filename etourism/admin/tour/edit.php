<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// التحقق من أن `tour_id`, `programme_id`, `tour_date`, `start_time`, `end_time`, و `max_participants` قد تم إرسالهم في الطلب
if (
    isset($_POST["tour_id"]) &&
    isset($_POST["programme_id"]) &&
    isset($_POST["tour_date"]) &&
    isset($_POST["start_time"]) &&
    isset($_POST["end_time"]) &&
    isset($_POST["max_participants"])
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $tour_id = htmlspecialchars(strip_tags($_POST["tour_id"]));
    $programme_id = htmlspecialchars(strip_tags($_POST["programme_id"]));
    $tour_date = htmlspecialchars(strip_tags($_POST["tour_date"]));
    $start_time = htmlspecialchars(strip_tags($_POST["start_time"]));
    $end_time = htmlspecialchars(strip_tags($_POST["end_time"]));
    $max_participants = htmlspecialchars(strip_tags($_POST["max_participants"]));

    // تحقق من وجود الرحلة بناءً على `tour_id`
    $stmtCheck = $con->prepare("SELECT * FROM tour WHERE tour_id = ?");
    $stmtCheck->execute([$tour_id]);

    // التحقق مما إذا كانت الرحلة موجودة
    if ($stmtCheck->rowCount() > 0) {
        // تحديث معلومات الرحلة
        $stmtUpdate = $con->prepare("UPDATE tour SET programme_id = ?, tour_date = ?, start_time = ?, end_time = ?, max_participants = ? WHERE tour_id = ?");
        $success = $stmtUpdate->execute([$programme_id, $tour_date, $start_time, $end_time, $max_participants, $tour_id]);

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'Tour information updated successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to update tour information.'));
        }
    } else {
        // إذا لم يتم العثور على الرحلة، نعيد حالة الفشل
        echo json_encode(array('status' => 'fail', 'message' => 'Tour not found.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'Tour ID, programme ID, tour date, start time, end time, and max participants are required.'));
}
?>
