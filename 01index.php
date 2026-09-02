<?php //require __DIR__.'/config.php'; require __DIR__.'.../partials/pagination.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Big Dataset Browser</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
<div class="d-flex align-items-center justify-content-between mb-3">
<h1 class="h4 m-0">Records (10M+ ready)</h1>
<a class="btn btn-outline-secondary" href="http://localhost:9090" target="_blank">Open phpMyAdmin</a>
</div>


<?php
$limit = isset($_GET['limit']) ? max(10, (int)$_GET['limit']) : 50;
$afterId = isset($_GET['after']) ? (int)$_GET['after'] : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;


$result = fetch_keyset_page($pdo, $limit, $afterId, $category);
$rows = $result['rows'];
$next = $result['nextAfterId'];
?>


<form class="row g-2 mb-3">
<div class="col-auto">
<input type="text" class="form-control" name="category" placeholder="Filter category" value="<?= htmlspecialchars($category ?? '') ?>">
</div>
<div class="col-auto">
<select name="limit" class="form-select">
<?php foreach ([25,50,100,250,500] as $opt): ?>
<option value="<?= $opt ?>" <?= $limit==$opt?'selected':'' ?>><?= $opt ?>/page</option>
<?php endforeach; ?>
</select>
</div>
<div class="col-auto">
<button class="btn btn-primary" type="submit">Apply</button>
</div>
</form>


<div class="card shadow-sm">
<div class="table-responsive">
<table class="table table-sm table-striped align-middle mb-0">
<thead class="table-light">
<tr>
<th style="width:100px">ID</th>
<th>Title</th>
<th style="width:140px">Category</th>
<th style="width:180px">Created</th>
</tr>
</thead>
<tbody>
<?php foreach ($rows as $r): ?>
<tr>
<td><?= (int)$r['id'] ?></td>
<td><?= htmlspecialchars($r['title']) ?></td>
<td><span class="badge bg-secondary-subtle text-secondary-emphasis"><?= htmlspecialchars($r['category']) ?></span></td>
<td><?= htmlspecialchars($r['created_at']) ?></td>
</tr>
<?php endforeach; ?>
<?php if (empty($rows)): ?>
<tr><td colspan="4" class="text-center py-4 text-muted">No rows</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="card-footer d-flex justify-content-between align-items-center">
<div class="small text-muted">Showing <?= count($rows) ?> rows</div>
<div>
<?php if ($next): ?>
<a class="btn btn-outline-primary btn-sm" href="?<?= http_build_query(array_filter(['category'=>$category,'limit'=>$limit,'after'=>$next])) ?>">Next →</a>
<?php else: ?>
<button class="btn btn-outline-secondary btn-sm" disabled>End</button>
<?php endif; ?>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>