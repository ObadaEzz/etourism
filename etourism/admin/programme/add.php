<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

if (
    isset($_POST["name"]) &&
    isset($_POST["description"]) &&
    isset($_POST["start_date"]) &&
    isset($_POST["end_date"]) &&
    isset($_POST["price"]) &&
    isset($_POST["driver_id"]) &&
    isset($_POST["guide_id"]) // التأكد من وجود معرف السائق والمرشد
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $name = htmlspecialchars(strip_tags($_POST["name"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));
    $start_date = htmlspecialchars(strip_tags($_POST["start_date"]));
    $end_date = htmlspecialchars(strip_tags($_POST["end_date"]));
    $price = htmlspecialchars(strip_tags($_POST["price"]));
    $driver_id = htmlspecialchars(strip_tags($_POST["driver_id"]));
    $guide_id = htmlspecialchars(strip_tags($_POST["guide_id"]));

    // تحقق من أن السعر هو رقم صحيح
    if (!is_numeric($price) || $price < 0) {
        echo json_encode(array('status' => 'fail', 'message' => 'Price must be a non-negative number.'));
        exit();
    }

    // إعداد الاستعلام لإضافة البرنامج
    $stmt = $con->prepare("INSERT INTO programme (name, description, start_date, end_date, price, driver_id, guide_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // تنفيذ الاستعلام
    if ($stmt->execute([$name, $description, $start_date, $end_date, $price, $driver_id, $guide_id])) {
        echo json_encode(array('status' => 'success', 'message' => 'Programme added successfully.'));
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'Failed to add programme. Please try again later.'));
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'All fields are required.'));
}
?>
