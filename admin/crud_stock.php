<?php
include '../config/config.php';

// Add
// if (isset($_POST['add'])) {
//     $product_id = $_POST['product_id'];
//     $quantity = $_POST['quantity'];
//     $warehouse_id = $_POST['warehouse_id'];

//     $stmt = $conn->prepare("INSERT INTO ims_stock (product_id, quantity, warehouse_id) VALUES (?, ?, ?)");
//     $stmt->bind_param("iii", $product_id, $quantity, $warehouse_id);
//     $stmt->execute();
//     header("Location: stock.php");
//     exit;
// }

//Add new
if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $warehouse_id = $_POST['warehouse_id'];

    // Check if the product already exists in the same warehouse
    $check = $conn->prepare("SELECT stock_id, quantity FROM ims_stock WHERE product_id = ? AND warehouse_id = ?");
    $check->bind_param("ii", $product_id, $warehouse_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;

        $update = $conn->prepare("UPDATE ims_stock SET quantity = ? WHERE stock_id = ?");
        $update->bind_param("ii", $new_quantity, $row['stock_id']);
        $update->execute();
    } else {
        // Insert new row
        $insert = $conn->prepare("INSERT INTO ims_stock (product_id, quantity, warehouse_id) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $product_id, $quantity, $warehouse_id);
        $insert->execute();
    }

    header("Location: stock.php");
    exit;
}


// Edit
if (isset($_POST['edit'])) {
    $stock_id = $_POST['stock_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $warehouse_id = $_POST['warehouse_id'];

    $stmt = $conn->prepare("UPDATE ims_stock SET product_id=?, quantity=?, warehouse_id=? WHERE stock_id=?");
    $stmt->bind_param("iiii", $product_id, $quantity, $warehouse_id, $stock_id);
    $stmt->execute();
    header("Location: stock.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $stock_id = intval($_GET['delete']);
    $conn->query("DELETE FROM ims_stock WHERE stock_id = $stock_id");

    $conn->query("SET @new_id = 0");
    $conn->query("UPDATE ims_stock SET stock_id = (@new_id := @new_id + 1)");
    $conn->query("ALTER TABLE ims_stock AUTO_INCREMENT = 1");

    header("Location: stock.php");
    exit;
}
?>
