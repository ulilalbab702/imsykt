<?php
include '../config/config.php';

// Add
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO ims_warehouse (name, location) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $location);
    $stmt->execute();
    header("Location: warehouse.php");
    exit;
}

// Edit
if (isset($_POST['edit'])) {
    $id = $_POST['warehouse_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE ims_warehouse SET name=?, location=? WHERE warehouse_id=?");
    $stmt->bind_param("ssi", $name, $location, $id);
    $stmt->execute();
    header("Location: warehouse.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM ims_warehouse WHERE warehouse_id = $id");

    $conn->query("SET @new_id = 0");
    $conn->query("UPDATE ims_warehouse SET warehouse_id = (@new_id := @new_id + 1)");
    $conn->query("ALTER TABLE ims_warehouse AUTO_INCREMENT = 1");

    header("Location: warehouse.php");
    exit;
}
?>
