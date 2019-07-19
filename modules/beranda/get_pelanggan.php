<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    $query = "SELECT count(id_pelanggan) as jumlah FROM pelanggan";

    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Query Error: " . $mysqli->error . '-' . $mysqli->error);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $data = $result->fetch_assoc();

    echo number_format($data['jumlah']);

    $stmt->close();

    $mysqli->close();
} else {
    echo '<script>window.location="../../index.php"</script>';
}
