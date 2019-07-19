<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if(isset($_POST['id_penjualan'])) {
        $query = "DELETE FROM penjualan WHERE id_penjualan = ?";

        $stmt = $mysqli->prepare($query);

        $id_penjualan = trim($_POST['id_penjualan']);

        $stmt->bind_param("i", $id_penjualan);

        $stmt->execute();

        if($stmt) {
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