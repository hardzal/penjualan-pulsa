<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if (isset($_POST['id_pulsa'])) {
        $query = "UPDATE pulsa SET provider = ?, nominal = ?, harga = ? WHERE id_pulsa = ?";

        $stmt = $mysqli->prepare($query);

        $id_pulsa = trim($_POST['id_pulsa']);
        $provider = trim($_POST['provider']);
        $nominal  = trim($_POST['nominal']);
        $harga    = trim($_POST['harga']);

        $stmt->bind_param('siii', $provider, $nominal, $harga, $id_pulsa);

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
