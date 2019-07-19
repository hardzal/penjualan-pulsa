<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if(isset($_GET['id_pelanggan'])) {
        $query = "SELECT nama FROM pelanggan WHERE id_pelanggan = ?";

        $stmt = $mysqli->prepare($query);

        if(!$stmt) {
            die("Query Error: ". $mysqli->errno . "-". $mysqli->error);
        }

        $id_pelanggan = trim($_GET['id_pelanggan']);

        $stmt->bind_param('i', $id_pelanggan);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();
    } 
    $mysqli->close();

    echo json_encode($data);
} else {
    echo "<script>window.location = '../../index.php'</script>";
}