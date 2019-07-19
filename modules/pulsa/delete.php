<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if (isset($_POST['id_pulsa'])) {
        $query = "DELETE FROM pulsa WHERE id_pulsa = ?";

        $stmt = $mysqli->prepare($query);

        $id_pulsa = trim($_POST['id_pulsa']);

        $stmt->bind_param("i", $id_pulsa);

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
