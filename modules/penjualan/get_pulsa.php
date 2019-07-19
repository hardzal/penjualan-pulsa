<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../config/config.php';

    if(isset($_GET['id_pulsa'])) {
        $query = "SELECT harga FROM pulsa WHERE id_pulsa = ?";

        $stmt = $mysqli->prepare($query);

        if(!$stmt) {
            die("Query Error: ". $mysqli->errno . "-". $mysqli->error);
        }
        
        $id_pulsa = $_GET['id_pulsa'];

        $stmt->bind_param("i", $id_pulsa);

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $stmt->close();
    }
 
    $mysqli->close();

    echo json_encode($data);
} else {
    echo "<script>window.location='../../index.php'</script>";
}