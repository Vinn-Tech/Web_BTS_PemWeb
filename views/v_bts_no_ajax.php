<?php
require_once '../db/conn.php';

// Initialize variables
$limit = 10;
$offset = 0;
$page = 1;
$data = []; // Ensure $data is initialized

if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    $offset = ($page - 1) * $limit;
}

$query = "SELECT * FROM tb_ne LIMIT $limit OFFSET $offset";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM tb_ne WHERE id_bts LIKE '%$search%' OR kel_des LIKE '%$search%' OR kab_kota LIKE '%$search%' OR prov LIKE '%$search%' OR luas_desa LIKE '%$search%' OR total_ne LIKE '%$search%' OR rasio_ne LIKE '%$search%' OR total_ne_4g LIKE '%$search%' OR rasio_ne_4g LIKE '%$search%' OR kec LIKE '%$search%' LIMIT $limit OFFSET $offset";
}

// Execute query and fetch data
$sql = mysqli_query($conn, $query);
if ($sql) {
    $data = mysqli_fetch_all($sql, MYSQLI_ASSOC);
} else {
    // Handle query error
    $data = [];
    // Optionally log error: error_log(mysqli_error($conn));
}

// Count total records for pagination
$query_count = "SELECT COUNT(*) as jumlah FROM tb_ne";
if (isset($_GET['search'])) {
    $query_count = "SELECT COUNT(*) as jumlah FROM tb_ne WHERE id_bts LIKE '%$search%' OR kel_des LIKE '%$search%' OR kab_kota LIKE '%$search%' OR prov LIKE '%$search%' OR luas_desa LIKE '%$search%' OR total_ne LIKE '%$search%' OR rasio_ne LIKE '%$search%' OR total_ne_4g LIKE '%$search%' OR rasio_ne_4g LIKE '%$search%' OR kec LIKE '%$search%'";
}

$sql_count = mysqli_query($conn, $query_count);
if ($sql_count) {
    $count = mysqli_fetch_assoc($sql_count);
    $jumlah_halaman = ceil($count['jumlah'] / $limit);
} else {
    // Handle query error
    $jumlah_halaman = 1; // Default to 1 to avoid division by zero
    // Optionally log error: error_log(mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Data BTS</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
</head>
<body>
 <div class="container mt-5">
  <h1>Data BTS</h1>
  <hr>
  <div class="card p-5">
   <div class="table-responsive">
    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
     <thead>
      <tr>
       <th>ID BTS</th>
       <th>Kelurahan Desa</th>
       <th>Kabupaten Kota</th>
       <th>Provinsi</th>
       <th>Luas Desa</th>
       <th>Total Network Element</th>
       <th>Rasio Network Element</th>
       <th>Total Network Element 4G</th>
       <th>Rasio Network Element 4G</th>
       <th>Kecamatan</th>
      </tr>
     </thead>
     <tbody>
      <?php foreach ($data as $d) { ?>
      <tr>
       <td><?= $d['id_bts'] ?></td>
       <td><?= $d['kel_des'] ?></td>
       <td><?= $d['kab_kota'] ?></td>
       <td><?= $d['prov'] ?></td>
       <td><?= number_format((float)$d['luas_desa'], 2, '.', ',') ?> KM</td>
       <td><?= $d['total_ne'] ?></td>
       <td><?= $d['rasio_ne'] ?></td>
       <td><?= $d['total_ne_4g'] ?></td>
       <td><?= $d['rasio_ne_4g'] ?></td>
       <td><?= $d['kec'] ?></td>
      </tr>
      <?php } ?>
     </tbody>
    </table>
   </div>
  </div>
  <div class="mt-3">
   <?php if ($jumlah_halaman > 1) { ?>
   <nav>
    <ul class="pagination">
     <?php 
                    $range = 2; // Number of pages to show before and after the current page
                    $start = max(1, $page - $range);
                    $end = min($jumlah_halaman, $page + $range);

                    if ($page > 1) { ?>
     <li class="page-item">
      <a class="page-link" href="?page=<?= $page - 1 ?><?= isset($search) ? '&search=' . urlencode($search) : '' ?>"
       aria-label="Previous">
       <span aria-hidden="true">&laquo;</span>
      </a>
     </li>
     <?php } else { ?>
     <li class="page-item disabled">
      <span class="page-link" aria-label="Previous">
       <span aria-hidden="true">&laquo;</span>
      </span>
     </li>
     <?php }

                    if ($start > 1) { ?>
     <li class="page-item">
      <a class="page-link" href="?page=1<?= isset($search) ? '&search=' . urlencode($search) : '' ?>">1</a>
     </li>
     <?php if ($start > 2) { ?>
     <li class="page-item disabled">
      <span class="page-link">...</span>
     </li>
     <?php }
                    }

                    for ($i = $start; $i <= $end; $i++) { ?>
     <li class="page-item <?= $i == $page ? 'active' : '' ?>">
      <a class="page-link"
       href="?page=<?= $i ?><?= isset($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
     </li>
     <?php }

                    if ($end < $jumlah_halaman) { ?>
     <?php if ($end < $jumlah_halaman - 1) { ?>
     <li class="page-item disabled">
      <span class="page-link">...</span>
     </li>
     <?php } ?>
     <li class="page-item">
      <a class="page-link"
       href="?page=<?= $jumlah_halaman ?><?= isset($search) ? '&search=' . urlencode($search) : '' ?>"><?= $jumlah_halaman ?></a>
     </li>
     <?php }

                    if ($page < $jumlah_halaman) { ?>
     <li class="page-item">
      <a class="page-link" href="?page=<?= $page + 1 ?><?= isset($search) ? '&search=' . urlencode($search) : '' ?>"
       aria-label="Next">
       <span aria-hidden="true">&raquo;</span>
      </a>
     </li>
     <?php } else { ?>
     <li class="page-item disabled">
      <span class="page-link" aria-label="Next">
       <span aria-hidden="true">&raquo;</span>
      </span>
     </li>
     <?php } ?>
    </ul>
   </nav>
   <?php } ?>
  </div>
 </div>

 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-4WkB2KOtaRo+zP7Tx93GVpNdEldU/mHJ3nhF82YdF7ntR52uR4ShYY7Wx6Fbe75g" crossorigin="anonymous"></script>

 <script>
 $(document).ready(function() {
  $('#datatable').DataTable({
   // Disable server-side processing
   processing: false,
   serverSide: false,
   paging: false, // Disable pagination within DataTable as pagination is handled separately
   searching: false, // Disable searching within DataTable as searching is handled via the form
   info: false // Disable table information display
  });
 });
 </script>
</body>
</html>