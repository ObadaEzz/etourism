<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../../connect.php"; // تأكد من تعديل المسار إذا كان مختلفاً

// استعلام لاسترجاع جميع الرحلات
$stmt = $con->prepare("SELECT * FROM tour");
$stmt->execute();

// استرجاع البيانات
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// التحقق مما إذا كانت البيانات موجودة
if ($tours) {
    echo json_encode(array('status' => 'success', 'data' => $tours));
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No tours found.'));
}
?>
