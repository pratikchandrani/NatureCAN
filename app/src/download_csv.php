<?php
/**
 * CSV Download Handler
 * Exports filtered data as CSV file
 */

ob_start();
session_start();
include("config.php");
ob_end_clean();

// Verify CSRF token for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit('Invalid request');
    }
}

// Check if download is requested
if (!isset($_REQUEST['download'])) {
    http_response_code(400);
    exit('Invalid request');
}

// Rate limiting - max 10 downloads per minute
if (!isset($_SESSION['download_count'])) {
    $_SESSION['download_count'] = 0;
    $_SESSION['download_time'] = time();
}

if (time() - $_SESSION['download_time'] < 60) {
    if ($_SESSION['download_count'] >= 10) {
        http_response_code(429);
        exit('Too many download requests. Please wait.');
    }
    $_SESSION['download_count']++;
} else {
    $_SESSION['download_count'] = 1;
    $_SESSION['download_time'] = time();
}

// Sanitize and get search parameters
$params = [];
$conditions = ["1=1"];

$sr_no = isset($_REQUEST['sr_no']) ? trim($_REQUEST['sr_no']) : "";
$plant_name = isset($_REQUEST['plant_name']) ? trim($_REQUEST['plant_name']) : "";
$title = isset($_REQUEST['title']) ? trim($_REQUEST['title']) : "";
$pmid = isset($_REQUEST['pmid']) ? trim($_REQUEST['pmid']) : "";
$cancer_types = isset($_REQUEST['cancer_types']) ? trim($_REQUEST['cancer_types']) : "";
$study_types = isset($_REQUEST['study_types']) ? trim($_REQUEST['study_types']) : "";
$model_system = isset($_REQUEST['model_system']) ? trim($_REQUEST['model_system']) : "";
$experimental_techniques = isset($_REQUEST['experimental_techniques']) ? trim($_REQUEST['experimental_techniques']) : "";
$toxicity_and_side_effects = isset($_REQUEST['toxicity_and_side_effects']) ? trim($_REQUEST['toxicity_and_side_effects']) : "";
$generic_search = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : "";

// Build query with prepared statements
$query = "SELECT DISTINCT sr_no, plant_name, title, cancer_types, study_types,
          model_system, experimental_techniques, toxicity_and_side_effects, pmid
          FROM merged_output_d_20260122 WHERE";

// Generic search
if (!empty($generic_search)) {
    $conditions[] = "(
        plant_name LIKE :generic_search OR
        title LIKE :generic_search OR
        cancer_types LIKE :generic_search OR
        study_types LIKE :generic_search OR
        model_system LIKE :generic_search OR
        experimental_techniques LIKE :generic_search OR
        toxicity_and_side_effects LIKE :generic_search OR
        pmid LIKE :generic_search
    )";
    $params[':generic_search'] = '%' . $generic_search . '%';
}

// Specific field searches with parameterized queries
if (!empty($sr_no)) {
    $conditions[] = "sr_no LIKE :sr_no";
    $params[':sr_no'] = '%' . $sr_no . '%';
}

if (!empty($plant_name)) {
    $conditions[] = "(plant_name = :plant_name_exact OR plant_name LIKE :plant_name_start OR plant_name LIKE :plant_name_mid OR plant_name LIKE :plant_name_end)";
    $params[':plant_name_exact'] = $plant_name;
    $params[':plant_name_start'] = $plant_name . ',%';
    $params[':plant_name_mid'] = '%, ' . $plant_name . ',%';
    $params[':plant_name_end'] = '%, ' . $plant_name;
}

if (!empty($title)) {
    $conditions[] = "title LIKE :title";
    $params[':title'] = '%' . $title . '%';
}

if (!empty($cancer_types)) {
    $conditions[] = "(cancer_types = :cancer_exact OR cancer_types LIKE :cancer_start OR cancer_types LIKE :cancer_mid OR cancer_types LIKE :cancer_end)";
    $params[':cancer_exact'] = $cancer_types;
    $params[':cancer_start'] = $cancer_types . ',%';
    $params[':cancer_mid'] = '%, ' . $cancer_types . ',%';
    $params[':cancer_end'] = '%, ' . $cancer_types;
}

if (!empty($study_types)) {
    $conditions[] = "(study_types = :study_exact OR study_types LIKE :study_start OR study_types LIKE :study_mid OR study_types LIKE :study_end)";
    $params[':study_exact'] = $study_types;
    $params[':study_start'] = $study_types . ',%';
    $params[':study_mid'] = '%, ' . $study_types . ',%';
    $params[':study_end'] = '%, ' . $study_types;
}

if (!empty($model_system)) {
    $conditions[] = "model_system LIKE :model_system";
    $params[':model_system'] = '%' . $model_system . '%';
}

if (!empty($experimental_techniques)) {
    $conditions[] = "experimental_techniques LIKE :experimental_techniques";
    $params[':experimental_techniques'] = '%' . $experimental_techniques . '%';
}

if (!empty($toxicity_and_side_effects)) {
    $conditions[] = "toxicity_and_side_effects LIKE :toxicity";
    $params[':toxicity'] = '%' . $toxicity_and_side_effects . '%';
}

if (!empty($pmid)) {
    $conditions[] = "pmid = :pmid";
    $params[':pmid'] = $pmid;
}

$conditions[] = "plant_name IS NOT NULL";
$query .= implode(" AND ", $conditions) . " ORDER BY plant_name LIMIT 100000";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
} catch (PDOException $e) {
    error_log('CSV download query failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Download failed. Please try again.');
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=naturecan_data_' . date('Y-m-d_His') . '.csv');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for proper Excel UTF-8 encoding
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Add column headers
fputcsv($output, [
    'Sr No',
    'Plant Name',
    'Title',
    'Cancer Types',
    'Study Types',
    'Model System',
    'Experimental Techniques',
    'Toxicity and Side Effects',
    'PMID'
]);

// Add data rows
while ($row = $stmt->fetch()) {
    fputcsv($output, [
        $row['sr_no'] ?? '',
        $row['plant_name'] ?? '',
        $row['title'] ?? '',
        $row['cancer_types'] ?? '',
        $row['study_types'] ?? '',
        $row['model_system'] ?? '',
        $row['experimental_techniques'] ?? '',
        $row['toxicity_and_side_effects'] ?? '',
        $row['pmid'] ?? ''
    ]);
}

fclose($output);
exit();
