<?php
session_start();
include "db_connect.php";

// Check if payment_id is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard-admin.php?msg=" . urlencode("Invalid tenant payment ID."));
    exit;
}

$payment_id = intval($_GET['id']);

// Step 1: Get tenant username associated with payment
$query = "SELECT tenant_username FROM payments WHERE payment_id = ?";
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    header("Location: dashboard-admin.php?msg=" . urlencode("DB error (prepare): " . mysqli_error($conn)));
    exit;
}
mysqli_stmt_bind_param($stmt, "i", $payment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $tenant_username = $row['tenant_username'];
    mysqli_stmt_close($stmt);

    // Step 2: Delete from payments table
    $delete_payment = "DELETE FROM payments WHERE payment_id = ?";
    $stmt_del_payment = mysqli_prepare($conn, $delete_payment);
    if (!$stmt_del_payment) {
        header("Location: dashboard-admin.php?msg=" . urlencode("DB error (prepare delete payment): " . mysqli_error($conn)));
        exit;
    }
    mysqli_stmt_bind_param($stmt_del_payment, "i", $payment_id);
    if (!mysqli_stmt_execute($stmt_del_payment)) {
        mysqli_stmt_close($stmt_del_payment);
        header("Location: dashboard-admin.php?msg=" . urlencode("Error deleting payment: " . mysqli_stmt_error($stmt_del_payment)));
        exit;
    }
    mysqli_stmt_close($stmt_del_payment);

    // Step 3: Check and delete from users table
    $check_user = "SELECT tenant_username FROM users WHERE tenant_username = ?";
    $stmt_check = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt_check, "s", $tenant_username);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        mysqli_stmt_close($stmt_check);
        $delete_user = "DELETE FROM users WHERE tenant_username = ?";
        $stmt_del_user = mysqli_prepare($conn, $delete_user);
        mysqli_stmt_bind_param($stmt_del_user, "s", $tenant_username);
        if (mysqli_stmt_execute($stmt_del_user)) {
            $msg = "✅ Tenant payment and user deleted.";
        } else {
            $msg = "⚠️ Payment deleted, but user deletion failed.";
        }
        mysqli_stmt_close($stmt_del_user);
    } else {
        mysqli_stmt_close($stmt_check);
        $msg = "✅ Payment deleted. User not found.";
    }

} else {
    mysqli_stmt_close($stmt);
    $msg = "⚠️ Payment record not found.";
}

header("Location: dashboard-admin.php?msg=" . urlencode($msg));
exit;
?>
