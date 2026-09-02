<?php include("header.php");
include('header_navbar.php');
include('db_config_test.php');
require_once __DIR__ . '/constants.php';
?>
<title>Search page</title>
<style> 
  
/* Back Button Styles */
.back-button-container {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #20558a 0%, #1a4570 100%);
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 8px;
    font-family: Verdana, sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: linear-gradient(135deg, #1a4570 0%, #20558a 100%);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    transform: translateY(-2px);
    color: white;
}

.btn-back:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.btn-back i {
    font-size: 16px;
}

/* Responsive adjustment */
@media (max-width: 768px) {
    .back-button-container {
        top: 10px;
        left: 10px;
    }
    
    .btn-back {
        padding: 8px 16px;
        font-size: 13px;
    }
}

/*loading animations -- CSS/Style*/
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* background-color: rgba(255, 255, 255, 0.8);  Semi-transparent background overlay */
    display: flex;
    justify-content: center;
	vertical-align:center;
    align-items: center;
    z-index: 9999; /* Ensure the loading animation is on top of other content */
}

.loader {
    border: 5px solid #f3f3f3; /* Light gray border */
    border-top: 5px solid #3498db; /* Blue border on top */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite; /* Rotate the spinner */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


/* Reset some default styles */
body, h1, h2, h3, p, ul, ol, li {
    margin: auto;
    padding: 6px;
}


/* Apply some basic styles to the body */
body {
    font:14px verdana;
    background-color: #f4f4f4;
    color: #333;
   
}

/* Apply styles to the header */
.header {
    background-color: #20558a;
    color: #fff;
    text-align: center;
    padding: 10px;
    border-radius: 0px 5px 0px 5px;
}

.header h1 {
    margin: 0;
    font-size: 24px;
}

/* Apply styles to the main container */
.container {
    background-color: #fff;
    padding: 20px;
    margin: 20px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
	
}

/* Apply styles to the search form */
.search_page {
    margin-bottom: 20px;
}

.search_page select, .search_page input[type="text"], .search_page input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.search_page input[type="submit"] {
    background-color: #20558a;
    color: #fff;
    cursor: pointer;
}

/* Apply styles to the result output */
.result_output {
    font-size: 26px;
	font-family:verdana;
	font-style:normal;
	font-weight:700;
    margin-bottom: 1px;
	text-align:center;
}
.result_output_sub{color:#666; font:12px verdana; font-style: italic; font-weight:300; text-align:center; vertical-align:middle;}

/* Apply styles to result tables */
.results {
    width: 100%;
    max-width: 600px;
    margin: 0 auto 20px auto;
    border-collapse: separate;
    box-shadow: 0px 2px 5px rgba(0, 2, 0, 0.2);
    border-radius: 5px;
	display:block;
	overflow:auto;
}
 .results:hover{box-shadow: 1px 4px 5px rgba(0, 0, 0, 0.5); transition: box-shadow 0.3ms ease-in-out 0s;}

.results th {
    background-color: #20558a;
    color: #fff;
    padding:7px 10px;
    text-align: center;
    border-radius: 5px 5px 0px 0px;
}

.results td {
	/* font:15px verdana; */
    padding: 16px 20px;
    border-bottom: 4px solid #ccc;
    border-radius: 0px 0px 5px 5px;
	overflow:auto;
	min-height:30px;
	width:auto;
	
}
/* Add a text shadow to the counter on hover */
.results td:hover {
    /* text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); */
	/* background-image:url(../image/bckg_hexagons_small.png); */
	/* color:white; */
    /* border-bottom: 1px solid yellow; */
	border-bottom:4px solid yellow; 
	box-shadow: 1px 4px 5px rgba(0, 0, 0, 0.5); transition: box-shadow 0.3ms ease-in-out 0s;
	border-radius: 5px 5px;
	transition: box-shadow 0.3s ease-in-out 0s;
}

/* Apply styles to clickable links */
.results a {
    text-decoration: none;
    color: #20558a;
	/* border-radius:5px; */
	padding:4px 6px;
}

.results a:hover {
    text-decoration:2px yellow underline;
	/* font:15.1px verdana; */
	
	border-bottom:0.5px dotted #20558a;
	/* border-radius:10px; */
	transition-duration:  text-decoration: 30ms;
	color:#20558a;
	 /* text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); */
	  /* background-image:url(../image/bckg_hexagons_small.png); */

}

.form:hover{color:white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); }

/* Counter styles */
.counter {
   /* display: inline-block;*/
    padding: 4px 4px;
    background-color: #20558a;
	border-right: 1px solid yellow;
    color: #fff;
    border-radius: 10px;
    font-weight: bold;
    margin-left: 30px;
}
	.counter:hover{
		text-decoration:2px underline yellow;
		border-radius: 50px;
		padding: 4px 4px;
		background-color: #20558a;
		color: #fff;
		border-radius:10px;
		font-weight: bold;
		margin-left: 30px;
		transition: box-shadow 0.3s ease-in-out 0s;
		}

/* Apply styles to the "No Results Found" message */
.no-results {
    text-align: center;
    background-color: #fff;
    padding: 20px;
  /*  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);*/
    border-radius: 5px;
}

.no-results h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.no-results p {
    font-size: 16px;
	font-family:verdana;
	font-weight:bold;
    margin-top: 20px;
	/*text-decoration: 0.5px underline;*/
}
.no-results button{color:white; background:#20558a; padding: 1px 5px;width:auto;min-width:100px;height:30px; vertical-align:middle;border-radius:5px;}
.no-results input{caret-color:red; padding:1px 5px; width:100%;max-width:300px;height:30px; border-radius:5px; border: 0.2px solid #20558a;vertical-align:middle;}
.no-results h4{margin: 2px 0px 0px 0px;}
.no-results ul li {color:black; padding:2px 0px 0px 0px;}
/*.no-results ul {
    list-style-type: none;
    margin-left: 20px;
padding:none;
margin:unset;
 line-height: 1.4;
}*/
#horizontal-lines-sidepannel{
	color:#20558A;
	font-size:10px 9px;
	margin:none;
	}

/*restart button CSS and the animation*/
.btn_restart{
	color:white;
	background:linear-gradient(to right, rgb(198 213 228 / .2), rgb(0 0 0 /0)), url(../../image/bck-image1.JPG) no-repeat center;
	background-size: cover;
	/*background:#20558A;*/
	width:80px;
	height:25px;
	text-align:center;
	vertical-align:middle;
	font:14px Verdana;
		
	border-radius:5px;
	border:thin solid;
	background: #20558A;
	animation: mymove 0ms infinite;
	cursor:pointer;
	}
	
	/*optional keyframe for the rest button To get highlight(a-blink) when its pop-up*/
	@keyframes mymove {
  from {background-color: red;} 
  to {background-color: #20558A;}
	}
/* Add more styles as needed */

/* Responsive styles for search page */
@media (max-width: 768px) {
    .result_output {
        font-size: 20px;
    }
    .results {
        max-width: 100%;
    }
    .results td {
        padding: 12px 15px;
    }
    .counter {
        margin-left: 10px;
    }
    .container {
        margin: 10px;
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .result_output {
        font-size: 16px;
    }
    .result_output_sub {
        font-size: 11px;
    }
    .results td {
        padding: 10px 10px;
    }
    .results th {
        padding: 5px 8px;
        font-size: 13px;
    }
    .counter {
        margin-left: 5px;
        padding: 3px 3px;
        font-size: 12px;
    }
    .no-results h2 {
        font-size: 18px;
    }
    .container {
        margin: 5px;
        padding: 10px;
    }
}

  </style>



<!--Removing Back Button as we added header 
 Back Button 
<div class="back-button-container">
    <a href="homepage.php" class="btn-back">
        <i class="fa fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>
</div>-->

<!--<blockquote>--> 
<div class="container my-3" id="main-container">
   <!-- <h1 class="result_output">Search results for <em>" <?php //echo $_REQUEST['search']?> "</em></h1>--><?php // to call_out sub_heading //echo("");?>
    <div class="loader-container">
         <div class="loader"></div>
      </div>
		<?php
        $noresults = true;// For:: error_statement if_the condition value is 'non-zero'.
		////QUERY
		if (isset($_REQUEST["search"])) {
			$dataset = $_REQUEST["search"];
			
			$query = $_REQUEST["search"];
			$query_safe = mysqli_real_escape_string($conn, $query);
			$table = NATURECAN_TABLE;

			$sr_no = "SELECT COUNT(DISTINCT sr_no) as sr_no FROM `$table` WHERE `sr_no` LIKE '%$query_safe%'";
			$plant_name = "SELECT COUNT(DISTINCT plant_name) as plant_name FROM `$table` WHERE `plant_name` LIKE '%$query_safe%'";
			$title = "SELECT COUNT(DISTINCT title) as title FROM `$table` WHERE `title` LIKE '%$query_safe%'";

			$pmid = "SELECT COUNT(DISTINCT pmid) as pmid FROM `$table` WHERE `pmid` LIKE '%$query_safe%'";
			$cancer_type_cleaned = "SELECT COUNT(DISTINCT cancer_types) as cancer_types FROM `$table` WHERE `cancer_types` LIKE '%$query_safe%'";
			$study_type = "SELECT COUNT(DISTINCT study_types) as study_types FROM `$table` WHERE `study_types` LIKE '%$query_safe%'";
			$model_system = "SELECT COUNT(DISTINCT model_system) as model_system FROM `$table` WHERE `model_system` LIKE '%$query_safe%'";
			$experimental_techniques = "SELECT COUNT(DISTINCT experimental_techniques) as experimental_techniques FROM `$table` WHERE `experimental_techniques` LIKE '%$query_safe%'";
			$toxicity_and_side_effects = "SELECT COUNT(DISTINCT toxicity_and_side_effects) as toxicity_and_side_effects FROM `$table` WHERE `toxicity_and_side_effects` LIKE '%$query_safe%'";
			











	
			////CONNECTION
			$sr_no1 = mysqli_query($conn, $sr_no);
			$rowsrno= mysqli_fetch_assoc($sr_no1);
			$sr_no2 = $rowsrno["sr_no"];
			
			$plant_name1 = mysqli_query($conn, $plant_name);
			$rowpn= mysqli_fetch_assoc($plant_name1);
			$plant_name2 = $rowpn["plant_name"];

			$title1 = mysqli_query($conn, $title);
			$rowtt= mysqli_fetch_assoc($title1);
			$title2 = $rowtt["title"];

			// $cited_by_pmid1 = mysqli_query($conn, $cited_by_pmid);
			// $rowcp= mysqli_fetch_assoc($cited_by_pmid1);
			// $cited_by_pmid2 = $rowcp["cited_by_pmid"];

			$cancer_type_cleaned1 = mysqli_query($conn, $cancer_type_cleaned);
			$rowctc= mysqli_fetch_assoc($cancer_type_cleaned1);
			$cancer_type_cleaned2 = $rowctc["cancer_types"];

			$study_type1 = mysqli_query($conn, $study_type);
			$rowst= mysqli_fetch_assoc($study_type1);
			$study_type2 = $rowst["study_types"];

			$model_system1 = mysqli_query($conn, $model_system);
			$rowms= mysqli_fetch_assoc($model_system1);
			$model_system2 = $rowms["model_system"];

			$experimental_techniques1 = mysqli_query($conn, $experimental_techniques);
			$rowexperimental_techniques= mysqli_fetch_assoc($experimental_techniques1);
			$experimental_techniques2 = $rowexperimental_techniques["experimental_techniques"];

			$toxicity_and_side_effects1 = mysqli_query($conn, $toxicity_and_side_effects);
			$rowtoxicity_and_side_effects= mysqli_fetch_assoc($toxicity_and_side_effects1);
			$toxicity_and_side_effects2 = $rowtoxicity_and_side_effects["toxicity_and_side_effects"];
			
			$pmid1 = mysqli_query($conn, $pmid);
			$rowpmid = mysqli_fetch_assoc($pmid1);
			$pmid2 = $rowpmid["pmid"];	
			
			// $pmid1 = mysqli_query($conn, $pmid);
			// $rowpmid = mysqli_fetch_assoc($pmid1);
			// $pmid2 = $rowpmid["pmid"];


		
			////new_CONNECTIONS_gmtdb


			// error_statement ifthe condition value is 'greater than zero'.
			if ($sr_no2 > 0|| $plant_name2 > 0 || $title2 > 0  ||  $cancer_type_cleaned2 > 0 || $study_type2 > 0 || $model_system2 > 0 || 
			$experimental_techniques2 > 0 || $toxicity_and_side_effects2 > 0) {
				$noresults = false;
				}
		}
        if (!$noresults) {
			echo'<h1 class="result_output">Search results for <em>"' . htmlspecialchars($_REQUEST['search'], ENT_QUOTES, 'UTF-8') . '"</em></h1>';
			// <!-- Gene association results -->
          
			echo '<h3 class="result_output_sub">(Distinct search result from dataset)</h3>
			<hr id="horizontal-lines-sidepannel" color="#20558A" background-color="white"></hr> 
				<section style="padding:10px 0 0 0; display:flex; justify-content:center;"> 
					<table class="results box">
						<tr>
							<th class="title"> NatureCan</th>
						</tr>
						<tr>
							<td class="description">
								<li class="text-dark"><form action="nc_trial_table.php" method="get" name="plant_name"><a href="nc_trial_table.php?plant_name='.urlencode($query).'". target="_blank">Plant name  <span class="d_result counter">'. $plant_name2 .' </span></a></form></li>

                                <li class="text-dark"><form action="nc_trial_table.php" method="get" name="title"><a href="nc_trial_table.php?title='.urlencode($query).'". target="_blank">Title <span class="d_result counter">'. $title2 .' </span></a></form></li>

								<li><form action="nc_trial_table.php" method="get" name="cancer_types"><a href="nc_trial_table.php?cancer_types='.urlencode($query).'". target="_blank">Cancer Types <span class="d_result counter">'. $cancer_type_cleaned2 . '</span></a></form></li>

								<li class="text-dark"><form action="nc_trial_table.php" method="get" name="study_types"><a href="nc_trial_table.php?study_types='.urlencode($query).'". target="_blank">Study types  <span class="d_result counter">'. $study_type2 .' </span></a></form></li>

								<li><form action="nc_trial_table.php" method="get" name="model_system"><a href="nc_trial_table.php?model_system='.urlencode($query).'". target="_blank">Model system <span class="d_result counter">' . $model_system2 . '</span></a></form></li>

								<li><form action="nc_trial_table.php" method="get" name="experimental_techniques"><a href="nc_trial_table.php?experimental_techniques='.urlencode($query).'". target="_blank">Experimental techniques <span class="d_result counter"> ' . $experimental_techniques2 . '</span></a></form></li>

								<li><form action="nc_trial_table.php" method="get" name="toxicity_and_side_effects"><a href="nc_trial_table.php?toxicity_and_side_effects='.urlencode($query).'". target="_blank">Toxicity and side effects <span class="d_result counter"> ' . $toxicity_and_side_effects2 .'</span></a></form></li>
								
							</td>
						</tr>
					</table>
				</section>';

		} else { if ($noresults){
					echo '<div class="no-results" id="container">
						<br>
						<h2 class="nr_head">No Results Found for <i>"' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '"</i></h2>
						<hr id="horizontal-lines-sidepannel" color="#20558A" background-color="white"></hr> 
						<br/>
							
							
						<p>Try searching again</p>
						<br/>
						<form class="search_page" method="get" action="search_4.php">
							<input class="form-control" name="search" type="search" placeholder="Search NatureCan" aria-label="Search">
							<button class="btn btn-sucess" type="submit"><i class="fa fa-search"></i> search</button>
						</form>
						<br/>
						<h4> <u>Suggestions </u></h4>
							<ul>
								<li>Make sure that all words are spelled correctly.</li>
								<li>Try different keywords.</li>
								<li>Try more general keywords.</li>
							</ul>
							<br/>	
						<div align="center" style="color: #fff; border: none;padding: 10px 20px;border-radius: 5px; cursor: pointer;font-size: 14px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">			
							<br/><a href="homepage.php"><button onclick="location.href = \'search.php\';" id="myButton" class="btn_restart float-left submit-button"><i class="fa fa-home"></i> Home<i class="fa fa-reset"></i></button></a>
						</div>
							
						
					</div>';
				}
			}
        ?>

</div>
<!--- below the script {type::javascript} is for the loading-animation--->
			<script>
                 window.onload = function() {
                // Hide the loading animation when the page and all its resources have finished loading
                document.querySelector(".loader-container").style.display = "none";
            };
            </script>
<!--</blockquote>-->
<?php include("footer.php");?>