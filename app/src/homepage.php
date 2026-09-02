<?php
// Include DB connection if needed later
include("db_config_test.php");
require_once __DIR__ . '/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>NatureCAN | Medicinal Plants in Cancer Care</title>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P0Z003MZ9T"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-P0Z003MZ9T');
</script>
<style>
/* Modern Colorful UI */
:root {
  --primary: #3a7d44; /* Soft Green */
  --secondary: #6bbf59; /* Light Green */
  --accent: #a8e6a1; /* Pale Leaf */
  --light: #f6fff5; /* Light natural bg */
  --dark: #2d3a2d; /* Dark moss */
  --white: #ffffff;
}

/* New HTML tag added to adjust Background Image Width and height */
html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
}

/* For White Green Background */
body {
  font-family: "Poppins", sans-serif;
  background: var(--light);
  color: var(--dark);
} 

header {
  text-align: center;
  padding: 200px 0 30px 0;  /* Increased top padding for larger logos */
  background: url("images/generated-image.png") no-repeat center center;
  background-size: cover;
  color: var(--white);
  border-bottom-left-radius: 30px;
  border-bottom-right-radius: 30px;
  position: relative;
}

header::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(58, 125, 68, 0.6); /*Changed from 0.6 to 0.85*/
  border-bottom-left-radius: 30px;
  border-bottom-right-radius: 30px;
}

header * {
  position: relative;
  z-index: 1;
}

/* Updated header-logos for 3 logos with equal spacing */


.header-logos {
  position: absolute;
  top: 20px;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 2;
  padding: 0 50px;
}

.logo-item:nth-child(2) {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.logo-item:nth-child(2) img {
  height: 250px;  /* Increased from the default 180px */
}


.logo-item {
  flex: 0 0 auto;
}

.logo-item img {
  height: 180px;  /* Increased from 90px */
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));  /* Strong shadow around logos */
}

/* Optional: Make logos slightly smaller on tablets */
@media (max-width: 1024px) {
  .logo-item img {
    height: 100px;
  }
  
  .header-logos {
    gap: clamp(60px, 12vw, 150px);
  }
}

/* Make logos even smaller on mobile */
@media (max-width: 768px) {
  .header-logos {
    padding: 0 20px;
    gap: clamp(30px, 8vw, 80px);
  }
  
  .logo-item img {
    height: 70px;
  }
  
  header {
    padding: 130px 0 30px 0;
  }
}

/* For NatureCAN main title  --- previous font-size - 9rem*/   
/* 1) Home page dimension doesn't autofit in available space.  made it autoscaling. */

h1 {
  font-size: clamp(3rem, 8vw, 7rem);
  /* font-size: 9rem; */
  margin: 0;
  font-weight: 600;
  text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.8);  /* Stronger shadow */
}


/* For Database .... main title  --- previous font-size - 9rem*/   

.subtitle {
  font-size: 1.5rem;
  margin: 0;
  opacity: 0.9;
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);  /* Stronger shadow */  /*Changed White to Black 20260102*/
}

.top-nav .nav-btn {
  display: inline-block;
  margin: 15px;
  padding: 10px 25px;
  background: var(--accent);
  border-radius: 20px;
  text-decoration: none;
  color: var(--dark);
  font-weight: bold;
  transition: 0.3s;
}
.top-nav .nav-btn:hover {
  background: #dd6b20;
  transform: translateY(-2px);
}

/* Description change to 20060103 - 4 pm to reduce space between Search and about*/

.description {
  width: 80%;
  margin: 10px auto 30px auto;  /* Reduced top margin, kept bottom margin */
  text-align: center;
}

/* List Cards */
.lists {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin: 30px auto;
  flex-wrap: wrap;
  max-height: 300px;
  overflow-y: auto;
}

/* Added .list-group on 20/01/2026 for border */

.list-group {
  background: var(--white);
  padding: 25px;
  border-radius: 20px;
  border: 2px solid var(--primary);  /* ADD THIS LINE */
  box-shadow: 
    0 2px 4px rgba(0, 0, 0, 0.05),
    0 4px 8px rgba(0, 0, 0, 0.08),
    0 8px 16px rgba(0, 0, 0, 0.1),
    0 16px 32px rgba(0, 0, 0, 0.08);
  width: 250px;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}


/* Subtle gradient overlay - like GPU shader effects */
.list-group::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, 
    rgba(107, 191, 89, 0.03) 0%, 
    rgba(58, 125, 68, 0.06) 100%);
  opacity: 0;
  transition: opacity 0.4s ease;
  pointer-events: none;
  z-index: 0;
}

.list-group:hover::before {
  opacity: 1;
}

/* Enhanced hover effect - like progressive enhancement in web rendering */
.list-group:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 
    0 4px 8px rgba(0, 0, 0, 0.08),
    0 8px 16px rgba(0, 0, 0, 0.12),
    0 16px 32px rgba(0, 0, 0, 0.15),
    0 24px 48px rgba(58, 125, 68, 0.2);
  border: 1px solid rgba(107, 191, 89, 0.2);
}

/* Inner glow effect on hover - like LED backlight */
.list-group:hover {
  box-shadow: 
    inset 0 0 20px rgba(107, 191, 89, 0.1),
    0 4px 8px rgba(0, 0, 0, 0.08),
    0 8px 16px rgba(0, 0, 0, 0.12),
    0 16px 32px rgba(0, 0, 0, 0.15),
    0 24px 48px rgba(58, 125, 68, 0.25);
}

/* Enhanced button with shadows */
.list-btn {
  width: 100%;
  max-width: 100%;
  padding: 12px 0;
  background: linear-gradient(135deg, var(--primary) 0%, #2d6635 100%);
  color: var(--white) !important;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  margin: 0 0 15px 0;
  transition: all 0.3s ease;
  text-decoration: none;
  display: block;
  box-shadow: 
    0 2px 4px rgba(0, 0, 0, 0.15),
    0 4px 8px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1;
  text-align: center;
  box-sizing: border-box;
}

/*Added for whitetext in Plant cancer type naturecan button - 20260107 - 9.04*/

.list-btn:visited,
.list-btn:link,
.list-btn:active {
  color: var(--white) !important;
}

.list-btn:hover {
  background: linear-gradient(135deg, var(--secondary) 0%, #5aa84a 100%);
  transform: translateY(-2px);
  box-shadow: 
    0 4px 8px rgba(0, 0, 0, 0.2),
    0 8px 16px rgba(107, 191, 89, 0.3);
}

.list-btn:active {
  transform: translateY(0);
  box-shadow: 
    0 1px 2px rgba(0, 0, 0, 0.15),
    0 2px 4px rgba(0, 0, 0, 0.1);
}


/* Enhanced list items with better spacing */
.list-group ul {
  list-style: none;
  padding: 0;
  position: relative;
  z-index: 1;
}

.list-group ul li {
  padding: 10px 0;
  font-weight: 600;
  color: var(--dark);
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
}

.list-group:hover ul li {
  color: #1a4d1a;
}

/* Search */
/* Reduce difference betweeen  ABout and Search bar - 20260103 - 04 pm */

.search-section {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 20px auto 10px auto;  /* Reduced top and bottom margins */
  width: 100%;
  padding: 10px 20px;  /* Reduced top/bottom padding */
}

.searchcontainer {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
}

.search_form {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-width: 700px;
  margin: 0 auto;
}

.search-area {
  width: 100%;
  max-width: 600px;
  padding: 12px 20px;
  border: 2px solid var(--primary);
  border-radius: 25px;
  font-size: 16px;
  outline: none;
  margin-right: 10px;
}

.srch_btn {
  padding: 12px 20px;
  background: var(--secondary);
  color: var(--white);
  border: 2px solid #000000;  /* Changed from 'none' to black border  - 20260103*/
  border-radius: 25px;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.srch_btn:hover {
  background: var(--primary);
}

.dropdown {
    display: none;
}
#globalSearch {
  width: 50%;
  padding: 12px;
  border: 2px solid var(--primary);
  border-radius: 8px;
  font-size: 1rem;
}
.search-btn {
  padding: 12px 20px;
  background: var(--secondary);
  color: var(--white);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin-left: 5px;
  transition: 0.3s;
}
.search-btn:hover {
  background: var(--primary);
}
.recommendation {
  font-size: 0.85rem;
  color: #e53e3e;
  margin-top: 5px;
  text-align: center;
}

</style>
<link rel="stylesheet" href="<?php echo FA_CSS_URL; ?>" integrity="<?php echo FA_CSS_SRI; ?>" crossorigin="anonymous">
</head>
<body>
<header>
    
    <div class="header-logos">
        <div class="logo-item">
          <a href="https://actrec.gov.in/home" target="_blank">
            <img src="images/ACTREC_logo1.png" alt="ACTREC Logo">
          </a>
        </div>
        
        <div class="logo-item">
          <a href="YOUR_LINK_HERE_2" target="_blank">
            <img src="images/Ministry_AYUSH.png" alt="Ministry AYUSH Logo">
          </a>
        </div>
        
        <div class="logo-item">
          <a href="https://tmc.gov.in/" target="_blank">
            <img src="images/TMC_Logo1.png" alt="TMC Logo">
          </a>        
        </div>
    </div>
<h1>NatureCAN</h1>
<p class="subtitle">Database of evidence-based Medicinal Plants in Cancer Care</p>
<nav class="top-nav">
<a href="homepage.php" class="nav-btn">Home</a>
<a href="statistics.php" class="nav-btn">Statistics</a>
<a href="nc_trial_table.php" class="nav-btn">NatureCAN DB</a>
<a href="about.php" class="nav-btn">About</a>
</nav>
</header>
<main>

<section class="search-section">
  <link rel="stylesheet" href="<?php echo FA_CSS_URL; ?>" integrity="<?php echo FA_CSS_SRI; ?>" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <form class="searchcontainer" id="searchbararea" method="get" action="nc_trial_table.php">    
    <div class="search_form">
      <input type="search" class="search-area" name="search" id="searchbararea" autoComplete="on"
        placeholder="Search" />
      <button class="srch_btn" onclick="search()" type="submit"><i class="fa fa-search"
          style=" font-size:14px;"></i></button>
    </div>
    <br>
  </form>
</section>

<section class="description">
<h2>About NatureCAN</h2>
<p>Curated evidence-based information on medicinal plants and phytochemicals used in cancer therapy.</p>
</section>

<section class="lists">
<div class="list-group">
<a href="plants_data.php" class="list-btn">Plants</a>
<ul><li>Aloe Vera</li><li>Curcuma Longa</li><li>Withania Somnifera</li></ul>
</div>

<div class="list-group">
<a href="nc_trial_table.php?search=" class="list-btn">NatureCAN</a>
<ul><li style="font-weight: bold;">Database of evidence-based Medicinal Plants in Cancer Care</li> </ul>
</div>

<div class="list-group">
<a href="cancer_types.php" class="list-btn">Cancer Types</a>
<ul><li>Breast Cancer</li><li>Lung Cancer</li><li>Liver Cancer</li></ul>
</div>
</section>

</main>

<?php include('footer.php'); ?>