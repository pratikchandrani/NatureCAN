<?php
// get_plant_stats.php - API endpoint for chart data
session_start();
include("db_config_test.php");

header('Content-Type: application/json');

// Build query based on current session filters (same logic as main page)
$query = "SELECT plant_name, cancer_types, study_types FROM merged_output_d_20260106 WHERE 1";

// Get search parameters from session
$generic_search = isset($_SESSION['generic_search']) ? $_SESSION['generic_search'] : "";
$sr_no = isset($_SESSION["sr_no"]) ? $_SESSION["sr_no"] : "";
$plant_name = isset($_SESSION["plant_name"]) ? $_SESSION["plant_name"] : "";
$title = isset($_SESSION["title"]) ? $_SESSION["title"] : "";
$cancer_types = isset($_SESSION["cancer_types"]) ? $_SESSION["cancer_types"] : "";
$study_types = isset($_SESSION["study_types"]) ? $_SESSION["study_types"] : "";
$model_system = isset($_SESSION["model_system"]) ? $_SESSION["model_system"] : "";
$experimental_techniques = isset($_SESSION["experimental_techniques"]) ? $_SESSION["experimental_techniques"] : "";
$toxicity_and_side_effects = isset($_SESSION["toxicity_and_side_effects"]) ? $_SESSION["toxicity_and_side_effects"] : "";
$pmid = isset($_SESSION["pmid"]) ? $_SESSION["pmid"] : "";

// Apply same filters as main table
if (!empty($generic_search)) {
    $generic_search_safe = mysqli_real_escape_string($conn, $generic_search);
    $query .= " AND (
        plant_name LIKE '%$generic_search_safe%' OR
        title LIKE '%$generic_search_safe%' OR
        cancer_types LIKE '%$generic_search_safe%' OR
        study_types LIKE '%$generic_search_safe%' OR
        model_system LIKE '%$generic_search_safe%' OR
        experimental_techniques LIKE '%$generic_search_safe%' OR
        toxicity_and_side_effects LIKE '%$generic_search_safe%' OR
        pmid LIKE '%$generic_search_safe%'
    )";
}

if (!empty($sr_no)) {
    $query .= " AND (sr_no LIKE '%$sr_no%')";
}

if (!empty($plant_name)) {
    $query .= " AND (plant_name = '$plant_name' OR plant_name LIKE '$plant_name,%' OR plant_name LIKE '%, $plant_name,%' OR plant_name LIKE '%, $plant_name')";
}

if (!empty($title)) {
    $title_safe = mysqli_real_escape_string($conn, $title);
    $query .= " AND title LIKE '%$title_safe%'";
}

if (!empty($cancer_types)) {
    $query .= " AND (cancer_types = '$cancer_types' OR cancer_types LIKE '$cancer_types,%' OR cancer_types LIKE '%, $cancer_types,%' OR cancer_types LIKE '%, $cancer_types')";
}

if (!empty($study_types)) {
    $query .= " AND (study_types = '$study_types' OR study_types LIKE '$study_types,%' OR study_types LIKE '%, $study_types,%' OR study_types LIKE '%, $study_types')";
}

if (!empty($model_system)) {
    $model_system_safe = mysqli_real_escape_string($conn, $model_system);
    $query .= " AND model_system LIKE '%$model_system_safe%'";
}

if (!empty($experimental_techniques)) {
    $experimental_techniques_safe = mysqli_real_escape_string($conn, $experimental_techniques);
    $query .= " AND experimental_techniques LIKE '%$experimental_techniques_safe%'";
}

if (!empty($toxicity_and_side_effects)) {
    $toxicity_and_side_effects_safe = mysqli_real_escape_string($conn, $toxicity_and_side_effects);
    $query .= " AND toxicity_and_side_effects LIKE '%$toxicity_and_side_effects_safe%'";
}

if (!empty($pmid)) {
    $query .= " AND pmid = '$pmid'";
}

$query .= " AND plant_name IS NOT NULL";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

// Group data by plant name
$plantData = [];

while ($row = mysqli_fetch_assoc($result)) {
    $plantName = trim($row['plant_name']);
    
    if (!isset($plantData[$plantName])) {
        $plantData[$plantName] = [
            'plant_name' => $plantName,
            'cancer_types' => '',
            'study_types' => ''
        ];
    }
    
    // Accumulate cancer types
    if (!empty($row['cancer_types'])) {
        $existingCancers = $plantData[$plantName]['cancer_types'];
        $plantData[$plantName]['cancer_types'] = empty($existingCancers) 
            ? $row['cancer_types'] 
            : $existingCancers . ', ' . $row['cancer_types'];
    }
    
    // Accumulate study types
    if (!empty($row['study_types'])) {
        $existingStudies = $plantData[$plantName]['study_types'];
        $plantData[$plantName]['study_types'] = empty($existingStudies) 
            ? $row['study_types'] 
            : $existingStudies . ', ' . $row['study_types'];
    }
}

// Convert to indexed array
$output = array_values($plantData);

echo json_encode($output);

mysqli_close($conn);
?>