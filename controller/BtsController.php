<?php

require_once '../db/conn.php';



if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
    $limit = $_POST['length'] ?? 10; // Ambil data limit per page
    $start = $_POST['start']; // Ambil data start
    $order_index = $_POST['order'][0]['column'] ?? 0; // Untuk mengambil index yg menjadi acuan untuk sorting
    $order_field = $_POST['columns'][$order_index]['data'] ?? '';
    $order_ascdesc = $_POST['order'][0]['dir'] ?? '';
    $order = $order_field ? ' ORDER BY '.$order_field.' '.$order_ascdesc : ' ORDER BY id_bts DESC';
    // $start_date = $_POST['start_date'] ?? ''; // Ambil start date dari filter tanggal
    // $end_date = $_POST['end_date'] ?? ''; // Ambil end date dari filter tanggal

    // Query untuk menghitung seluruh data
    $sql = mysqli_query($conn, 'SELECT * FROM tb_ne');
    $sql_count = mysqli_num_rows($sql);

    // Query untuk pencarian
    $query = "SELECT * FROM tb_ne WHERE (id_bts LIKE '%".$search."%' 
    OR id_bts LIKE '%".$search."%' 
    OR kel_des LIKE '%".$search."%' 
    OR kab_kota LIKE '%".$search."%' 
    OR prov LIKE '%".$search."%' 
    OR luas_desa LIKE '%".$search."%' 
    OR total_ne LIKE '%".$search."%' 
    OR rasio_ne LIKE '%".$search."%'
    OR total_ne_4g LIKE '%".$search."%'
    OR rasio_ne_4g LIKE '%".$search."%'
    OR kec LIKE '%".$search."%'
    )";

    // // Jika ada filter tanggal, tambahkan ke query dengan BETWEEN
    // if (!empty($start_date) && !empty($end_date)) {
    //     $query .= " AND tanggal BETWEEN '".$start_date."' AND '".$end_date."'";
    // } elseif (!empty($start_date)) {
    //     $query .= " AND tanggal >= '".$start_date."'";
    // } elseif (!empty($end_date)) {
    //     $query .= " AND tanggal <= '".$end_date."'";
    // }

    // Query untuk data yang akan di tampilkan
    $sql_data = mysqli_query($conn, $query.$order.' LIMIT '.$limit.' OFFSET '.$start);
    $sql_filter = mysqli_query($conn, $query); // Query untuk count jumlah data sesuai dengan filter pada textbox pencarian
    $sql_filter_count = mysqli_num_rows($sql_filter); // Hitung data yg ada pada query $sql_filter

    $data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC); // Untuk mengambil data hasil query menjadi array

    $callback = [
        'draw' => $_POST['draw'], // Ini dari datatablenya
        'recordsTotal' => $sql_count,
        'recordsFiltered' => $sql_filter_count,
        'data' => $data,
    ];

    header('Content-Type: application/json');
    echo json_encode($callback); // Convert array $callback ke json
}