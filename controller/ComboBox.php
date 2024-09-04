<?php
require_once '../db/conn.php';


if ($_POST['act'] == 'getKelurahan') {
    $sql = mysqli_query($conn, "SELECT DISTINCT prov FROM tb_ne WHERE prov LIKE '%".$_POST['search']."%'");
    $data = [];
    while ($row = mysqli_fetch_assoc($sql)) {
        $data[] = [
            'id' => $row['prov'],
            'text' => $row['prov']
        ];
    }
    echo json_encode($data);
    exit;
}