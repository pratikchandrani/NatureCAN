<?php 
// Start the session FIRST
session_start();

// --- CSRF Protection ---
// Generate a token if one does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verify CSRF token on POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Handle invalid token. You might want to log this event.
        die('Invalid CSRF token. Please try again.');
    }
}
// --- End CSRF Protection ---

// Handle reset action BEFORE anything else
if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    // Clear ALL search-related session variables
    unset(
        $_SESSION['plant_name'],
        $_SESSION['title'],
        $_SESSION['cancer_types'],
        $_SESSION['study_types'],
        $_SESSION['model_system'],
        $_SESSION['experimental_techniques'],
        $_SESSION['toxicity_and_side_effects'],
        $_SESSION['pmid'],
        $_SESSION['sr_no'],
        $_SESSION['generic_search'],
        $_SESSION['current_page'],
        $_SESSION['total_rows'],
        $_SESSION['query_where_clause']
    );
    
    // Redirect to clean URL without reset parameter
    header("Location: nc_trial_table.php");
    exit();
}

// Then include files
include("db_config_test.php");
require_once __DIR__ . '/constants.php';
include('header.php');
include('header_navbar.php');

// Initialize variables from session/request with safe defaults
$current_page = isset($_REQUEST['pgno']) ? intval($_REQUEST['pgno']) : (isset($_SESSION['current_page']) ? intval($_SESSION['current_page']) : 1);
if ($current_page < 1) $current_page = 1;
$_SESSION['current_page'] = $current_page;

$itemsPerPage = isset($_REQUEST['itemsPerPage']) ? intval($_REQUEST['itemsPerPage']) : 15;
if ($itemsPerPage < 1) $itemsPerPage = 15;

$offset = ($current_page - 1) * $itemsPerPage;
if ($offset < 0) $offset = 0;

// ADDED: Handle generic search parameter
if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
    // Clear all previous session variables when new generic search is performed
    unset($_SESSION['plant_name'], $_SESSION['title'], $_SESSION['cancer_types'], 
          $_SESSION['study_types'], $_SESSION['model_system'], $_SESSION['experimental_techniques'], 
          $_SESSION['toxicity_and_side_effects'], $_SESSION['pmid'], $_SESSION['sr_no']);
    
    // Store the generic search term
    $_SESSION['generic_search'] = $_REQUEST['search'];
    
    // Reset to page 1 for new search
    $_SESSION['current_page'] = 1;
}

// Handle specific field searches - update session and support cumulative filtering
$field_names = ['plant_name', 'title', 'cancer_types', 'study_types', 'model_system', 'experimental_techniques', 'toxicity_and_side_effects', 'pmid'];
$field_search_submitted = false;

foreach ($field_names as $field) {
    if (isset($_REQUEST[$field])) {
        $field_search_submitted = true;
        $val = trim($_REQUEST[$field]);
        if ($val !== '') {
            // Save the new search value to session
            $_SESSION[$field] = $val;
        } else {
            // User cleared this field - remove it from session
            unset($_SESSION[$field]);
        }
    }
    // Fields NOT in the current request keep their existing session values (cumulative filtering)
}

if ($field_search_submitted) {
    // Clear generic search when using field-specific search
    unset($_SESSION['generic_search']);
    // Reset to page 1 for new search
    $_SESSION['current_page'] = 1;
    $current_page = 1;
    $offset = 0;
}

// Initialize local search variables from session AFTER all POST handling
$generic_search = isset($_SESSION['generic_search']) ? $_SESSION['generic_search'] : '';
$sr_no = isset($_SESSION['sr_no']) ? $_SESSION['sr_no'] : '';
$plant_name = isset($_SESSION['plant_name']) ? $_SESSION['plant_name'] : '';
$title = isset($_SESSION['title']) ? $_SESSION['title'] : '';
$cancer_types = isset($_SESSION['cancer_types']) ? $_SESSION['cancer_types'] : '';
$study_types = isset($_SESSION['study_types']) ? $_SESSION['study_types'] : '';
$model_system = isset($_SESSION['model_system']) ? $_SESSION['model_system'] : '';
$experimental_techniques = isset($_SESSION['experimental_techniques']) ? $_SESSION['experimental_techniques'] : '';
$toxicity_and_side_effects = isset($_SESSION['toxicity_and_side_effects']) ? $_SESSION['toxicity_and_side_effects'] : '';
$pmid = isset($_SESSION['pmid']) ? $_SESSION['pmid'] : '';
?>

<!---STYLE SHEET FOR Ncan TABLE -->
<style type="text/css">
    <?php 
    include('style_nc_table.css');
    ?>
    
    .loader-container {
        position: relative;
        top: 0;
        left: 0;
        width: 200px;
        height: 200px;
        transform: translate(500%, 300%);
        background-color: rgba(255, 255, 255, 0.8);
        display: grid;
        justify-content: center;
        align-items: center;
        z-index: 99;
    }

    .loader {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 70px;
        height: 70px;
        animation: spin 1s linear infinite;
    }
        /* Style for plant name links - Added on 20260107 - 8.24 am*/
    .plant-link {
        color: #20558a;
        text-decoration: none;
        font-weight: 500;
    }

    .plant-link:hover {
        text-decoration: underline;
        color: #3498db;
    }

/* Added for Download button*/

    .btn_download {
    background-color: #28a745;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    }

    .btn_download:hover {
        background-color: #218838;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transform: translateY(-1px);
    }

    .btn_download:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .btn_download i {
        font-size: 16px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive table controls */
    .table-controls {
        font: 15px verdana;
        border-left: 0.5px dashed #20558A;
        border-right: 0.5px dashed #20558A;
        border-width: thin;
        padding: 0px 0px 0px 1px;
        display: inline-block;
        text-align: center;
        color: #333;
        margin-bottom: 1em;
    }
    .table-controls-inner {
        font: 15px verdana;
        border-left: 0.5px dashed #20558A;
        padding: 0px 0px 0px 0.2px;
        display: inline;
        text-align: center;
        color: #333;
    }
    .search-result-display {
        color: #20558a;
        position: relative;
        padding: 0px 14px 0px 0px;
        margin: 0;
        display: block;
        float: right;
        font: 15px Verdana;
    }

    @media (max-width: 768px) {
        .table-controls {
            float: none;
            display: block;
            width: 100%;
            text-align: center;
            font-size: 13px;
        }
        .search-result-display {
            float: none;
            text-align: center;
            font-size: 13px;
            padding: 5px 0;
        }
        .loader-container {
            transform: translate(100%, 200%);
        }
    }
    @media (max-width: 480px) {
        .table-controls {
            font-size: 12px;
        }
        .search-result-display {
            font-size: 12px;
        }
        .loader-container {
            transform: translate(50%, 150%);
        }
    }
</style>
<script src="<?php echo JQUERY_URL; ?>"></script>
<script src="<?php echo BOOTSTRAP_JS_URL; ?>" integrity="<?php echo BOOTSTRAP_JS_SRI; ?>" crossorigin="anonymous"></script>

<div class="row-cont" id="mid-table">
    <div class="loader-container">
        <div class="loader"> </div>
        <div>
            <button onclick="location.href = 'nc_trial_table.php?reset=1';" id="myButton" class="btn_restart float-left submit-button">Reset<i class="fa fa-reset"></i></button>
        </div>
    </div>
    <div class="brdr-cont2">
        <div class="column middle">
            <?php
                // Construct the base query for counting rows
                $resna_query = "SELECT COUNT(sr_no) as num_row from merged_output_d_20260122 WHERE 1";
                $query = "SELECT DISTINCT sr_no, plant_name, title, cancer_types, study_types, model_system, experimental_techniques, toxicity_and_side_effects, pmid FROM merged_output_d_20260122 WHERE 1";

                // Check if search parameters have changed
                $search_changed = false;
                if (isset($_REQUEST['search']) || isset($_REQUEST['plant_name']) || isset($_REQUEST['title']) || isset($_REQUEST['cancer_types']) ||
                    isset($_REQUEST['study_types']) || isset($_REQUEST['model_system']) || isset($_REQUEST['experimental_techniques']) ||
                    isset($_REQUEST['toxicity_and_side_effects']) || isset($_REQUEST['pmid']) || isset($_REQUEST['reset'])) {
                    $search_changed = true;
                }

                if ($search_changed || !isset($_SESSION['total_rows'])) {
                    // --- Construct the WHERE clause ---
                    $where_clause = "";
                    // Generic search across all columns
                    if (!empty($_SESSION['generic_search'])) {
                        $generic_search_safe = mysqli_real_escape_string($conn, $_SESSION['generic_search']);
                        $where_clause .= " AND (
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
                    // Specific field searches (all values escaped)
                    if (!empty($_SESSION['sr_no'])) {
                        $safe_sr_no = mysqli_real_escape_string($conn, $_SESSION['sr_no']);
                        $where_clause .= " AND (sr_no LIKE '%$safe_sr_no%')";
                    }
                    if (!empty($_SESSION['plant_name'])) {
                        $safe_plant = mysqli_real_escape_string($conn, $_SESSION['plant_name']);
                        $where_clause .= " AND (plant_name LIKE '%$safe_plant%')";
                    }
                    if (!empty($_SESSION['title'])) $where_clause .= " AND title LIKE '%" . mysqli_real_escape_string($conn, $_SESSION['title']) . "%'";
                    if (!empty($_SESSION['cancer_types'])) {
                        $safe_cancer = mysqli_real_escape_string($conn, $_SESSION['cancer_types']);
                        $where_clause .= " AND (cancer_types LIKE '%$safe_cancer%')";
                    }
                    if (!empty($_SESSION['study_types'])) {
                        $safe_study = mysqli_real_escape_string($conn, $_SESSION['study_types']);
                        $where_clause .= " AND (study_types LIKE '%$safe_study%')";
                    }
                    if (!empty($_SESSION['model_system'])) $where_clause .= " AND model_system LIKE '%" . mysqli_real_escape_string($conn, $_SESSION['model_system']) . "%'";
                    if (!empty($_SESSION['experimental_techniques'])) $where_clause .= " AND experimental_techniques LIKE '%" . mysqli_real_escape_string($conn, $_SESSION['experimental_techniques']) . "%'";
                    if (!empty($_SESSION['toxicity_and_side_effects'])) $where_clause .= " AND toxicity_and_side_effects LIKE '%" . mysqli_real_escape_string($conn, $_SESSION['toxicity_and_side_effects']) . "%'";
                    if (!empty($_SESSION['pmid'])) $where_clause .= " AND pmid = '" . mysqli_real_escape_string($conn, $_SESSION['pmid']) . "'";

                    $resna_query .= $where_clause;

                    $resna_result = mysqli_query($conn, $resna_query);
                    $row_count = mysqli_fetch_assoc($resna_result);
                    $_SESSION['total_rows'] = $row_count['num_row'];
                    $_SESSION['query_where_clause'] = $where_clause;
                }

                $total_rows = $_SESSION['total_rows'];
                $query .= $_SESSION['query_where_clause'];

                $safe_offset = intval($offset);
                $safe_limit = intval($itemsPerPage);
                $query .= " AND plant_name is not null ORDER BY plant_name LIMIT $safe_offset, $safe_limit";
                
                
                $result = mysqli_query($conn, $query);

                ?>


            <h2 class="trail_table_header"></h2>

            <div class="container-fluid">
                <style>
                    #navbar {
                        overflow: visible;
                    }

                    .content {
                        padding: 10%;
                        transition: 0.6s ease;
                    }

                    .sticky {
                        position: sticky;
                        display: inline-block;
                        top: 0;
                        width: 100%;
                        opacity: 1;
                        min-width: 100%;
                        max-width: 86%;
                        border-left: 2px groove #20558a;
                        border-right: 2px groove #20558a;
                        border-bottom: 0.5px groove #20558a;
                        border-radius: 5px;
                        backdrop-filter: blur(2.9px);
                        transition: 0.9s ease;
                        border-radius: 10px;
                    }

                    .sticky+.content {
                        padding-top: 20px;
                        top: 0;
                        font: 15px Verdana;
                    }
                </style>
                <script>
                    window.onscroll = function() {
                        myFunction()
                    };

                    var navbar = document.getElementById("navbar");
                    var sticky = navbar.offsetTop;

                    function myFunction() {
                        if (window.pageYOffset >= sticky) {
                            navbar.classList.add("sticky")
                        } else {
                            navbar.classList.remove("sticky");
                        }
                    }
                </script>

                <?php
                $sumPage = $offset + 1;
                $cntn = min($total_rows, $offset + $itemsPerPage);
                ?>
                
                <div class='num_rows table-controls' id='navbar'>
                    Showing: <?php echo "$sumPage - $cntn"; ?>
                    <div class="num_rows table-controls-inner">
                        <form method="post" action="nc_trial_table.php" id="dd_form">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <select name="itemsPerPage" class="dd_select" onchange="submitForm1();">
                                <option class='dd_option' value='5' <?php if ($itemsPerPage == 5) echo 'selected="selected"'; ?>>5</option>
                                <option class='dd_option' value='15' <?php if ($itemsPerPage == 15) echo 'selected="selected"'; ?>>15</option>
                                <option class='dd_option' value='30' <?php if ($itemsPerPage == 30) echo 'selected="selected"'; ?>>30</option>
                                <option class='dd_option' value='50' <?php if ($itemsPerPage == 50) echo 'selected="selected"'; ?>>50</option>
                                <option class='dd_option' value='70' <?php if ($itemsPerPage == 70) echo 'selected="selected"'; ?>>70</option>
                                <option class='dd_option' value='100' <?php if ($itemsPerPage == 100) echo 'selected="selected"'; ?>>100</option>
                                <option class='dd_option' value='500' <?php if ($itemsPerPage == 500 || $total_rows <= $itemsPerPage) echo 'selected="selected"'; ?>><?php echo min(500, $total_rows); ?></option>
                            </select>
                        </form>
                        <script type='text/javascript'>
                            function submitForm1() {
                                document.getElementById('dd_form').submit();
                            }
                        </script>
                    </div>
                    of &nbsp;<i style='width:3%;color:#333; font:15px Verdana; '><?php echo $total_rows; ?></i> entries &nbsp;
                </div>

                <?php
                $search_result = "";
                if (!empty($generic_search)) {
                    $search_result = htmlspecialchars($generic_search);
                } else {
                    if (!empty($sr_no)) $search_result .= htmlspecialchars($sr_no) . " | ";
                    if (!empty($plant_name)) $search_result .= htmlspecialchars($plant_name) . " | ";
                    if (!empty($title)) $search_result .= htmlspecialchars($title) . " | ";
                    if (!empty($cancer_types)) $search_result .= htmlspecialchars($cancer_types) . " | ";
                    if (!empty($study_types)) $search_result .= htmlspecialchars($study_types) . " | ";
                    if (!empty($model_system)) $search_result .= htmlspecialchars($model_system) . " | ";
                    if (!empty($experimental_techniques)) $search_result .= htmlspecialchars($experimental_techniques) . " | ";
                    if (!empty($toxicity_and_side_effects)) $search_result .= htmlspecialchars($toxicity_and_side_effects) . " | ";
                    if (!empty($pmid)) $search_result .= htmlspecialchars($pmid) . " | ";
                    $search_result = rtrim($search_result, " | ");
                }

                if (!empty($search_result)) {
                    echo "<div class='result_output search-result-display'>
                            <button onclick=\"location.href = 'nc_trial_table.php?reset=1';\" id='myButton' style='padding: 6px 10px; border:none; border-radius:10px; vertical-align:middle;'><i class='fas fa-redo-alt'></i></button>
                            | Search results for <b><em>\"" . $search_result . "\"</em></b> |
                        </div>";
                }
                ?>

                <?php
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-container table-brdr">
                        <table class="table_ga" id="myTable" cellpadding="0" border="0" bgcolor="#FFF">
                            <thead id="navbar">
                                <tr class="t_head">
                                    <th class="sh2" align="center">Plant name</th>
                                    <th class="sh3" align="center">Title</th>
                                    <th class="sh5" align="center">Cancer types</th>
                                    <th class="sh6" align="center">Study types</th>
                                    <th class="sh7" align="center">Model system</th>
                                    <th class="sh8" align="center">Experimental Techniques</th>
                                    <th class="sh9" align="center">Toxicity and Side effects</th>
                                    <th class="sh10" align="center">PMID</th>
                                </tr>

                                <tr class="table_search_area">
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="plant_name" value="<?php echo htmlspecialchars($plant_name); ?>" autocomplete="on" ></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="title" value="<?php echo htmlspecialchars($title); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>                                        
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="cancer_types" value="<?php echo htmlspecialchars($cancer_types); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="study_types" value="<?php echo htmlspecialchars($study_types); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="model_system" value="<?php echo htmlspecialchars($model_system); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="experimental_techniques" value="<?php echo htmlspecialchars($experimental_techniques); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="toxicity_and_side_effects" value="<?php echo htmlspecialchars($toxicity_and_side_effects); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="nc_trial_table.php" method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <div class="ls_form">
                                                <input type="search" class="tsb" id="search" name="pmid" value="<?php echo htmlspecialchars($pmid); ?>" autocomplete="on"></input>
                                                <button class="ls_button" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="scroll_bar tbody_content">
                                <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <tr>
                                            <!-- <td class="sh2" align="left"><?php echo !empty($row['plant_name']) ? ucfirst($row['plant_name']) : '-'; ?></td>  -->
                                            <td class="sh2" align="left"><?php 
                                                if (!empty($row['plant_name'])) {
                                                    // Split multiple plant names by comma
                                                    $plant_names = explode(',', $row['plant_name']);
                                                    $linked_names = array();
                                                    
                                                    foreach ($plant_names as $single_plant) {
                                                        $single_plant = trim($single_plant);
                                                        // URL encode the plant name for the IMPPAT link
                                                        $encoded_plant = rawurlencode($single_plant);
                                                        // Create the hyperlink
                                                        $imppat_url = "https://cb.imsc.res.in/imppat/phytochemical/" . $encoded_plant;
                                                        $linked_names[] = "<a href='" . $imppat_url . "' target='_blank' class='plant-link'>" . ucfirst(htmlspecialchars($single_plant)) . "</a>";
                                                    }
                                                    
                                                    echo implode(', ', $linked_names);
                                                } else {
                                                    echo '-';
                                                }
                                            ?></td> 
                                            <td class="sh3"><?php echo !empty($row['title']) ? ucfirst(htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh4"><?php echo !empty($row['cancer_types']) ? ucfirst(htmlspecialchars($row['cancer_types'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh5"><?php echo !empty($row['study_types']) ? ucfirst(htmlspecialchars($row['study_types'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh6"><?php echo !empty($row['model_system']) ? ucfirst(htmlspecialchars($row['model_system'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh7"><?php echo !empty($row['experimental_techniques']) ? ucfirst(htmlspecialchars($row['experimental_techniques'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh8"><?php echo !empty($row['toxicity_and_side_effects']) ? ucfirst(htmlspecialchars($row['toxicity_and_side_effects'], ENT_QUOTES, 'UTF-8')) : '-'; ?></td>
                                            <td class="sh9"><?php echo '<a href="https://pubmed.ncbi.nlm.nih.gov/' . htmlspecialchars($row["pmid"], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . htmlspecialchars($row["pmid"], ENT_QUOTES, 'UTF-8') . '</a>'; ?></td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table_footer">
                        <button onclick="location.href = 'nc_trial_table.php?reset=1';" id="myButton" class="btn_restart float-left submit-button">Reset<i class="fa fa-reset"></i></button>
                        
                        <!-- ADDed THIS LINE - 20260107 - 8.46 am -->
                        <button onclick="downloadCSV();" id="downloadBtn" class="btn_download submit-button" style="margin-left: 10px;">Download CSV <i class="fa fa-download"></i></button>


                        <div class="pg_brdr pagination_ga">
                            <?php
                            $totalPages = ceil($total_rows / $itemsPerPage);

                            echo ("<div style='color:#20558a; display:inline; font-weight:bold; width:1.5%; '> &nbsp;&nbsp; | &nbsp;</div>");

                            if ($current_page > 1) {
                                $prepage = $current_page - 1;
                                echo ("<a href='nc_trial_table.php?pgno=$prepage&itemsPerPage=$itemsPerPage'>&laquo; Prev</a>");
                                echo ("<a href='nc_trial_table.php?pgno=1&itemsPerPage=$itemsPerPage'>1</a>");
                                echo ("<span class='pga_hellip' style='color:red; text-align:center;'>&hellip;</span>");
                            } else {
                                echo ("<span class='pga_disabled'>&laquo; Prev </span>");
                            }

                            if ($totalPages > 1) {
                                $p1 = min($current_page + 2, $totalPages);
                                $p2 = $totalPages;

                                for ($i1 = max($current_page - 1, 1); $i1 <= $p1; $i1++) {
                                    if ($i1 <= $totalPages) {
                                        if ($i1 == $current_page) {
                                            echo ("<a href='nc_trial_table.php?pgno=$i1&itemsPerPage=$itemsPerPage' class='active'>$i1</a>");
                                        } else {
                                            echo ("<a href='nc_trial_table.php?pgno=$i1&itemsPerPage=$itemsPerPage'>$i1</a>");
                                        }
                                    }
                                }

                                if ($p1 < $p2) {
                                    echo ("<span class='pga_hellip' style='color:red; text-align:center;'>&hellip;</span>");
                                }
                                for ($i2 = max($p1 + 3, $totalPages); $i2 <= $totalPages; $i2++) {
                                    echo ("<a href='nc_trial_table.php?pgno=$i2&itemsPerPage=$itemsPerPage'>$i2</a>");
                                }
                            } else {
                                echo ("<a href='nc_trial_table.php?pgno=1&itemsPerPage=$itemsPerPage'>1</a>");
                            }

                            if ($current_page < $totalPages) {
                                $nextpage = $current_page + 1;
                                echo ("<a href='nc_trial_table.php?pgno=$nextpage&itemsPerPage=$itemsPerPage'>Next &raquo;</a>");
                            } elseif ($current_page == $totalPages) {
                                echo ("<span class='pga_disabled'> Next &raquo;</span>");
                            }

                            echo ("<div style='color:#20558a; display:inline; font-weight:bold; width:1.5%; '> &nbsp;&nbsp; | &nbsp;</div>");
                            ?>

                            </div> <!-- end pagination_ga -->
                    </div> <!-- end table_footer -->
                    <br />

                <?php
                } else {
                    // No results found section
                    echo ("<div class='error-message-container' align='center' style='text-align: center; background-color: #fff; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2); border-radius: 5px; padding: 10px; border: 1px solid #20558a; margin: 20px auto; max-width: 100%; font:13pt Verdana; display:inline-block; width:99%;'>
                            <br/><br/><br/>
                            No results found for <i style='font:13pt Verdana; font-style:bold; color: #20558a;'><b><em>\"" . htmlspecialchars($search_result) . "\"</em></b></i>.
                            <br/><br/>");
                    
                    echo "<p>Try searching again</p><br/>
                          <form class='search_page' method='get' action='nc_trial_table.php'>
                              <input class='form-control' name='search' type='search' placeholder='Search NatureCAN' aria-label='Search'>
                              <button class='btn btn-sucess' type='submit'><i class='fa fa-search'></i> search</button>
                          </form><br/>";
                ?>
                    <div align="center">
                        <button onclick="location.href = 'nc_trial_table.php?reset=1';" id="myButton" class="btn_restart float-left submit-button">Reset<i class="fa fa-reset"></i></button>
                    </div>
                    <?php 
                    echo ("<br/><div style='font-size: 14px; font-weight:bold;'> Suggestions </div>
                            <span style='list-style-type:none; margin-left: 20px; font-size:14px;'>
                                <li>Make sure that all words are spelled correctly.</li>
                                <li>Try different keywords.</li>
                                <li>Try more general keywords.</li>
                            </span>
                          <br/></div>"); 
                    ?>
                <?php
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $(".loader-container").hide();
                    });
                </script>
                <script>
                function downloadCSV() {
                    // Get all current search parameters
                    var params = new URLSearchParams();
                    
                    <?php
                    if (!empty($generic_search)) echo "params.append('search', " . json_encode($generic_search) . ");";
                    if (!empty($sr_no)) echo "params.append('sr_no', " . json_encode($sr_no) . ");";
                    if (!empty($plant_name)) echo "params.append('plant_name', " . json_encode($plant_name) . ");";
                    if (!empty($title)) echo "params.append('title', " . json_encode($title) . ");";
                    if (!empty($cancer_types)) echo "params.append('cancer_types', " . json_encode($cancer_types) . ");";
                    if (!empty($study_types)) echo "params.append('study_types', " . json_encode($study_types) . ");";
                    if (!empty($model_system)) echo "params.append('model_system', " . json_encode($model_system) . ");";
                    if (!empty($experimental_techniques)) echo "params.append('experimental_techniques', " . json_encode($experimental_techniques) . ");";
                    if (!empty($toxicity_and_side_effects)) echo "params.append('toxicity_and_side_effects', " . json_encode($toxicity_and_side_effects) . ");";
                    if (!empty($pmid)) echo "params.append('pmid', " . json_encode($pmid) . ");";
                    ?>
                    
                    params.append('download', '1');
                    
                    // Redirect to download
                    window.location.href = 'download_csv.php?' + params.toString();
                }
                </script>
            </div> <!-- end container-fluid -->
        </div> <!-- end column middle -->
    </div> <!-- end brdr-cont2 -->
</div> <!-- end row-cont -->

<?php include("footer.php"); ?>