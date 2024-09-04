<?php
require_once '../db/conn.php';

// Initialize variables for pagination and entries per page
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default to 10 records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max($page, 1); // Ensure page is at least 1
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify query to include search condition
$query = "SELECT * FROM tb_ne WHERE kel_des LIKE '%$search%' OR kab_kota LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
} else {
    $data = [];
}

// Count total records for pagination including the search term
$query_count = "SELECT COUNT(*) as jumlah FROM tb_ne WHERE kel_des LIKE '%$search%' OR kab_kota LIKE '%$search%'";
$result_count = mysqli_query($conn, $query_count);
$count = mysqli_fetch_assoc($result_count);
$total_records = $count['jumlah'];
$total_pages = ceil($total_records / $limit);

// Define range for pagination display
$range = 2;
$start = max(1, $page - $range);
$end = min($total_pages, $page + $range);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data BTS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        
</head>

<body>
    <div class="container mt-5">
        <h1>Data BTS Without AJAX</h1>
        <hr>
        <div class="card p-5">
            <!-- Form for search and limit -->
            <form method="GET" class="mb-3">
                <div class="row d-flex justify-content-between align-items-center" style="width:100%;">                    
                    <!-- Limit selection -->
                    <div class="col-md-4 d-flex align-items-center gap-2">
                        <label for="limit" class="form-label">Show</label>
                        <select id="limit" name="limit" class="form-select text-center" style="width:17%;" onchange="this.form.submit()">
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                        <label for="limit" class="form-label">entries</label>
                    </div>

                    <!-- Search form -->
                    <div class="col-md-4 d-flex align-items-center gap-2">
                        <div class="search-container d-flex justify-content-end align-items-center gap-2" style="width: 100%;">
                        <input type="text" id="search" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Cari">
                        <input type="hidden" name="page" value="<?= $page ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>

                </div>

            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width: 100%;">
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
                                <td><?= htmlspecialchars($d['id_bts']) ?></td>
                                <td><?= htmlspecialchars($d['kel_des']) ?></td>
                                <td><?= htmlspecialchars($d['kab_kota']) ?></td>
                                <td><?= htmlspecialchars($d['prov']) ?></td>
                                <td><?= number_format((float)$d['luas_desa'], 2, '.', ',') ?> KM</td>
                                <td><?= htmlspecialchars($d['total_ne']) ?></td>
                                <td><?= htmlspecialchars($d['rasio_ne']) ?></td>
                                <td><?= htmlspecialchars($d['total_ne_4g']) ?></td>
                                <td><?= htmlspecialchars($d['rasio_ne_4g']) ?></td>
                                <td><?= htmlspecialchars($d['kec']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page -->
                        <?php if ($page > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </span>
                            </li>
                        <?php } ?>

                        <!-- First Page -->
                        <?php if ($start > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>">1</a>
                            </li>
                            <?php if ($start > 2) { ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <!-- Page Numbers -->
                        <?php for ($i = $start; $i <= $end; $i++) { ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                            </li>
                        <?php } ?>

                        <!-- Last Page -->
                        <?php if ($end < $total_pages) { ?>
                            <?php if ($end < $total_pages - 1) { ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php } ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $total_pages ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>"><?= $total_pages ?></a>
                            </li>
                        <?php } ?>

                        <!-- Next Page -->
                        <?php if ($page < $total_pages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Next">
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
            </div>
        </div>
    </div>
</body>

</html>
