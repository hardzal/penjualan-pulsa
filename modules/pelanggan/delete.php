<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if (isset($_POST['id_pelanggan'])) {
        $query = "DELETE FROM pelanggan WHERE id_pelanggan = ?";

        $stmt  = $mysqli->prepare($query);

        $id_pelanggan = $_POST['id_pelanggan'];

        $stmt->bind_param("i", $id_pelanggan);

        $stmt->execute();

        if ($stmt) {
            echo "sukses";
        } else {
            echo "gagal";
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    echo "<script>window.location='../../index.php'</script>";
}
