<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

if (
    isset($_POST["programme_id"]) &&
    isset($_POST["tour_date"]) &&
    isset($_POST["start_time"]) &&
    isset($_POST["end_time"]) &&
    isset($_POST["max_participants"]) &&
    isset($_POST["image_url"]) // إضافة حقل الصورة
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $programme_id = htmlspecialchars(strip_tags($_POST["programme_id"]));
    $tour_date = htmlspecialchars(strip_tags($_POST["tour_date"]));
    $start_time = htmlspecialchars(strip_tags($_POST["start_time"]));
    $end_time = htmlspecialchars(strip_tags($_POST["end_time"]));
    $max_participants = htmlspecialchars(strip_tags($_POST["max_participants"]));
    $image_url = htmlspecialchars(strip_tags($_POST["image_url"])); // إضافة حقل الصورة

    // تحقق من أن العدد الأقصى للمشاركين هو رقم صحيح
    if (!is_numeric($max_participants) || $max_participants <= 0) {
        echo json_encode(array('status' => 'fail', 'message' => 'Max participants must be a positive number.'));
        exit();
    }

    // إعداد الاستعلام لإضافة الرحلة
    $stmt = $con->prepare("INSERT INTO tour (programme_id, tour_date, start_time, end_time, max_participants, image_url) VALUES (?, ?, ?, ?, ?, ?)");

    // تنفيذ الاستعلام
    if ($stmt->execute([$programme_id, $tour_date, $start_time, $end_time, $max_participants, $image_url])) {
        echo json_encode(array('status' => 'success', 'message' => 'Tour added successfully.'));
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'Failed to add tour. Please try again later.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'All fields are required.'));
}
?>
