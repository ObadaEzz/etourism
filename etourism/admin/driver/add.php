<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

if (
    isset($_POST["fName"]) &&
    isset($_POST["lName"]) &&
    isset($_POST["plateNumber"]) &&
    isset($_POST["description"])
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $fName = htmlspecialchars(strip_tags($_POST["fName"]));
    $lName = htmlspecialchars(strip_tags($_POST["lName"]));
    $plateNumber = htmlspecialchars(strip_tags($_POST["plateNumber"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));

    // تحقق مما إذا كان السائق موجوداً بالفعل بناءً على plateNumber
    $checkStmt = $con->prepare("SELECT * FROM driver WHERE plateNumber = ?");
    $checkStmt->execute([$plateNumber]);

    if ($checkStmt->rowCount() > 0) {
        // إذا تم العثور على سائق بنفس رقم اللوحة
        echo json_encode(array('status' => 'fail', 'message' => 'Driver already exists.'));
    } else {
        // إعداد الاستعلام لإضافة السائق
        $stmt = $con->prepare("INSERT INTO driver (fName, lName, plateNumber, description) VALUES (?, ?, ?, ?)");

        // تنفيذ الاستعلام
        if ($stmt->execute([$fName, $lName, $plateNumber, $description])) {
            echo json_encode(array('status' => 'success', 'message' => 'Driver added successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to add driver.'));
        }
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'All fields are required.'));
}
?>
