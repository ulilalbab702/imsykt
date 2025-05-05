<?php
include '../config/config.php';

// Add
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO ims_supplier (name, contact, address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $contact, $address);
    $stmt->execute();
    header("Location: supplier.php");
    exit;
}

// Edit
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE ims_supplier SET name=?, contact=?, address=? WHERE supplier_id=?");
    $stmt->bind_param("sssi", $name, $contact, $address, $id);
    $stmt->execute();
    header("Location: supplier.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM ims_supplier WHERE supplier_id = $id");

    // Penyesuaian ID agar tetap urut
    $conn->query("SET @new_id = 0");
    $conn->query("UPDATE ims_supplier SET supplier_id = (@new_id := @new_id + 1)");
    $conn->query("ALTER TABLE ims_supplier AUTO_INCREMENT = 1");

    header("Location: supplier.php");
    exit;
}
?>
