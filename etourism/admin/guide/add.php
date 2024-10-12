<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

if (
    isset($_POST["fName"]) &&
    isset($_POST["lName"]) &&
    isset($_POST["address"]) && // تغيير plateNumber إلى address
    isset($_POST["mobile"]) && // إضافة mobile
    isset($_POST["description"])
) {
    // استخدام htmlspecialchars و strip_tags لحماية البيانات
    $fName = htmlspecialchars(strip_tags($_POST["fName"]));
    $lName = htmlspecialchars(strip_tags($_POST["lName"]));
    $address = htmlspecialchars(strip_tags($_POST["address"])); // تغيير plateNumber إلى address
    $mobile = htmlspecialchars(strip_tags($_POST["mobile"])); // إضافة mobile
    $description = htmlspecialchars(strip_tags($_POST["description"]));

    // تحقق مما إذا كان المرشد موجوداً بالفعل بناءً على رقم الهاتف
    $checkStmt = $con->prepare("SELECT * FROM guide WHERE mobile = ?"); // تغيير إلى guide و mobile
    $checkStmt->execute([$mobile]);

    if ($checkStmt->rowCount() > 0) {
        // إذا تم العثور على مرشد بنفس رقم الهاتف
        echo json_encode(array('status' => 'fail', 'message' => 'Guide already exists.'));
    } else {
        // إعداد الاستعلام لإضافة المرشد
        $stmt = $con->prepare("INSERT INTO guide (fName, lName, Address, mobile, description) VALUES (?, ?, ?, ?, ?)");

        // تنفيذ الاستعلام
        if ($stmt->execute([$fName, $lName, $address, $mobile, $description])) {
            echo json_encode(array('status' => 'success', 'message' => 'Guide added successfully.'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to add guide.'));
        }
    }
} else {
    echo json_encode(array('status' => 'empty', 'message' => 'All fields are required.'));
}
?>
