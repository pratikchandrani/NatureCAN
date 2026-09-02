<?php
include('Nconnect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Plants Data Viewer</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Plants Data Viewer</h1>

<input type="text" id="searchInput" placeholder="Search..." onkeyup="filterTable()">

<div class="table-container">
<table id="plantsTable">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Plant Name</th>
            <th>Title</th>
            <th>PMID</th>
            <th>cancer_type_cleaned</th>
            <th>study type</th>
            <th>model_system</th>
            <th>Cited By PMIDs</th>
            <th>experimental_techniques</th>
            <th>toxicity_and_side_effects</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach($plants as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['sr_no']) ?></td>
            <td><?= htmlspecialchars($row['plant_name']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['pmid']) ?></td>

            <td><?= htmlspecialchars($row['cited_by_pmid']) ?></td>
            <td><?= htmlspecialchars($row['cancer_type_cleaned']) ?></td>
            <td><?= htmlspecialchars($row['study_type']) ?></td>
            <td><?= htmlspecialchars($row['model_system']) ?></td>
            <td><?= htmlspecialchars($row['cited_by_pmids']) ?></td>
            <td><?= htmlspecialchars($row['experimental_techniques']) ?></td>
            <td><?= htmlspecialchars($row['toxicity_and_side_effects']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<script src="script.js"></script>
</body>
</html>
