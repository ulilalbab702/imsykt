<?php
include '../config/config.php';

function uploadImage($fileInput) {
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== 0) {
        return null;
    }

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileTmp = $_FILES[$fileInput]['tmp_name'];
    $fileName = uniqid() . "_" . basename($_FILES[$fileInput]["name"]);
    $targetFile = $targetDir . $fileName;

    $imageInfo = getimagesize($fileTmp);
    if ($imageInfo === false) return null;

    $srcImage = match ($imageInfo['mime']) {
        'image/jpeg' => imagecreatefromjpeg($fileTmp),
        'image/png' => imagecreatefrompng($fileTmp),
        'image/gif' => imagecreatefromgif($fileTmp),
        default => null
    };

    $dstImage = imagecreatetruecolor(300, 300);
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, 300, 300, $imageInfo[0], $imageInfo[1]);

    match ($imageInfo['mime']) {
        'image/jpeg' => imagejpeg($dstImage, $targetFile, 85),
        'image/png' => imagepng($dstImage, $targetFile),
        'image/gif' => imagegif($dstImage, $targetFile),
    };

    imagedestroy($srcImage);
    imagedestroy($dstImage);

    return $fileName;
}

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $supplier = intval($_POST['supplier_id']);
    $price = floatval($_POST['price']);
    $image = uploadImage('image');
    $created = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO ims_product (name, description, supplier_id, price, image, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $name, $desc, $supplier, $price, $image, $created);
    $stmt->execute();
    header("Location: product.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $supplier = intval($_POST['supplier_id']);
    $price = floatval($_POST['price']);
    $image = uploadImage('image');

    if (!$image) {
        $res = $conn->query("SELECT image FROM ims_product WHERE product_id = $id");
        $image = $res->fetch_assoc()['image'];
    }

    $stmt = $conn->prepare("UPDATE ims_product SET name=?, description=?, supplier_id=?, price=?, image=? WHERE product_id=?");
    $stmt->bind_param("sssdsi", $name, $desc, $supplier, $price, $image, $id);
    $stmt->execute();
    header("Location: product.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image FROM ims_product WHERE product_id = $id");
    $img = $res->fetch_assoc()['image'];
    if ($img && file_exists("../uploads/" . $img)) unlink("../uploads/" . $img);
    $conn->query("DELETE FROM ims_product WHERE product_id = $id");

    $conn->query("SET @new_id = 0");
    $conn->query("UPDATE ims_product SET product_id = (@new_id := @new_id + 1)");
    $conn->query("ALTER TABLE ims_product AUTO_INCREMENT = 1");

    header("Location: product.php");
    exit;
}
?>
