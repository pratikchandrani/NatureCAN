<?php
// Include DB connection if needed later
include("db_config_test.php");
?>
<?php 
include('header_navbar.php');  // Using the new header with navigation
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Statistics | NatureCAN</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
<style>
/* CSS Variables */
:root {
  --primary: #3a7d44;
  --secondary: #6bbf59;
  --accent: #a8e6a1;
  --light: #f6fff5;
  --dark: #2d3a2d;
  --white: #ffffff;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", "Segoe UI", sans-serif;
  color: var(--dark);
  overflow-x: hidden;
  position: relative;
  min-height: 100vh;
  background: #f5f5f5;
}

/* All Content Container */
.content-wrapper {
  position: relative;
  z-index: 10;
  min-height: 100vh;
  padding-top: 20px;
}

/* Page Header */
.page-header {
  text-align: center;
  padding: 40px 20px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  margin: 30px auto;
  max-width: 1400px;
  border-radius: 20px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.page-header h1 {
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 10px;
  font-weight: 700;
}

.page-header p {
  font-size: 1rem;
  color: var(--dark);
  line-height: 1.6;
}

/* Statistics Container */
.stats-container {
  max-width: 1600px;
  margin: 0 auto;
  padding: 20px;
}

/* TAB NAVIGATION */
.tab-navigation {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin: 30px auto;
  max-width: 600px;
  background: rgba(255, 255, 255, 0.95);
  padding: 15px;
  border-radius: 50px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.tab-btn {
  flex: 1;
  padding: 15px 30px;
  background: transparent;
  border: 2px solid var(--primary);
  border-radius: 30px;
  color: var(--primary);
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.tab-btn:hover {
  background: rgba(58, 125, 68, 0.1);
  transform: translateY(-2px);
}

.tab-btn.active {
  background: var(--primary);
  color: white;
  box-shadow: 0 4px 15px rgba(58, 125, 68, 0.3);
}

.tab-btn i {
  font-size: 1.2rem;
}

/* TAB CONTENT */
.tab-content {
  display: none;
  animation: fadeIn 0.5s ease-in;
}

.tab-content.active {
  display: block;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Graph Cards - 4 per row layout */
.graphs-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* Fixed 4 columns */
  gap: 25px;
  margin-bottom: 50px;
}

.graph-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  transition: all 0.4s;
  border-top: 4px solid var(--primary);
  display: flex;
  flex-direction: column;
  height: 100%; /* Ensures all cards have same height */
}

.graph-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(58, 125, 68, 0.25);
}

.graph-title {
  font-size: 1.1rem;
  color: var(--primary);
  margin-bottom: 8px;
  font-weight: 600;
  text-align: center;
  min-height: 50px; /* Ensures consistent title height */
  display: flex;
  align-items: center;
  justify-content: center;
}

.graph-description {
  font-size: 0.85rem;
  color: #555;
  margin-bottom: 15px;
  line-height: 1.4;
  text-align: center;
  font-style: italic;
  min-height: 60px; /* Ensures consistent description height */
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Square Image Container - Perfect centering */
.image-container {
  width: 100%;
  aspect-ratio: 1 / 1; /* Perfect square */
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  flex-grow: 1; /* Takes remaining space in card */
}

.graph-image {
  max-width: 95%;
  max-height: 95%;
  width: auto;
  height: auto;
  object-fit: contain; /* Maintains aspect ratio */
  cursor: pointer;
  transition: transform 0.3s;
}

.graph-image:hover {
  transform: scale(1.05);
}

/* Tables Section */
.table-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  margin-bottom: 40px;
  transition: all 0.4s;
  overflow-x: auto;
}

.table-card:hover {
  box-shadow: 0 15px 40px rgba(58, 125, 68, 0.25);
}

.table-title {
  font-size: 1.8rem;
  color: var(--primary);
  margin-bottom: 10px;
  font-weight: 600;
  text-align: center;
}

.table-description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 25px;
  line-height: 1.6;
  text-align: center;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  overflow: hidden;
  border-radius: 10px;
  min-width: 600px;
}

.data-table thead {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
}

.data-table th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  font-size: 1rem;
  white-space: nowrap;
}

.data-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #e0e0e0;
}

.data-table tbody tr {
  transition: background 0.3s;
}

.data-table tbody tr:hover {
  background: rgba(107, 191, 89, 0.1);
}

.data-table tbody tr:nth-child(even) {
  background: rgba(246, 255, 245, 0.5);
}

/* Download Button */
.download-btn {
  display: inline-block;
  margin-top: 15px;
  padding: 10px 20px;
  background: var(--primary);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.download-btn:hover {
  background: var(--secondary);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(58, 125, 68, 0.3);
}

.download-btn i {
  margin-right: 8px;
}

/* Modal for enlarged images */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.9);
  animation: fadeIn 0.3s;
}

.modal-content {
  margin: auto;
  display: block;
  max-width: 90%;
  max-height: 90%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  object-fit: contain;
}

.close-modal {
  position: absolute;
  top: 30px;
  right: 50px;
  color: #f1f1f1;
  font-size: 50px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
  z-index: 10000;
}

.close-modal:hover {
  color: #bbb;
}


/* Edited on 05/01/2026 - 6 pm  */ 
/* Footer
footer {
  background: linear-gradient(135deg, rgba(27, 94, 32, 0.95), rgba(46, 125, 50, 0.95));
  backdrop-filter: blur(10px);
  color: var(--white);
  padding: 30px 20px;
  margin-top: 60px;
  text-align: center;
}

.footer-content p {
  font-size: 0.9rem;
  margin: 8px 0;
  line-height: 1.6;
} */

/* Responsive Design */
@media (max-width: 1400px) {
  .graphs-grid {
    grid-template-columns: repeat(3, 1fr); /* 3 columns on medium screens */
  }
}

@media (max-width: 1024px) {
  .graphs-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns on tablets */
  }
  
  .graph-title {
    font-size: 1rem;
    min-height: 45px;
  }
  
  .graph-description {
    font-size: 0.8rem;
    min-height: 55px;
  }
}

@media (max-width: 768px) {
  .graphs-grid {
    grid-template-columns: 1fr; /* 1 column on mobile */
  }
  
  .page-header h1 {
    font-size: 2rem;
  }
  
  .tab-navigation {
    flex-direction: column;
    gap: 10px;
  }
  
  .tab-btn {
    font-size: 1rem;
  }
  
  .data-table {
    font-size: 0.9rem;
  }
  
  .data-table th, .data-table td {
    padding: 8px;
  }

  .close-modal {
    top: 15px;
    right: 25px;
    font-size: 35px;
  }
}

@media (max-width: 480px) {
  .graph-card {
    padding: 15px;
  }
  
  .graph-title {
    font-size: 0.95rem;
    min-height: 40px;
  }
  
  .graph-description {
    font-size: 0.75rem;
    min-height: 50px;
  }
}

/* Special adjustment for exactly 5 images */
.graphs-grid .graph-card:nth-last-child(1):nth-child(5) {
  grid-column: 2 / 3; /* Centers the 5th image in second row */
}
</style>
</head>
<body>

<!-- MAIN CONTENT -->
<div class="content-wrapper">
  
  <!-- Page Header -->
  <div class="page-header">
    <h1>Database Statistics</h1>
    <p>Comprehensive analysis of medicinal plants and their applications in cancer care from NatureCAN database.</p>
  </div>
  
  <!-- Statistics Container -->
  <div class="stats-container">
    
    <!-- TAB NAVIGATION -->
    <div class="tab-navigation">
      <button class="tab-btn active" onclick="openTab(event, 'visual-analytics')">
        <i class="fas fa-chart-bar"></i>
        Visual Analytics
      </button>
      <button class="tab-btn" onclick="openTab(event, 'data-tables')">
        <i class="fas fa-table"></i>
        Data Tables
      </button>
    </div>
    
    <!-- VISUAL ANALYTICS TAB -->
    <div id="visual-analytics" class="tab-content active">
      <div class="graphs-grid">
        
        <!-- Graph 1: Top 10 Plants -->
        <div class="graph-card">
          <h3 class="graph-title">1. Medicinal Plants studied Articles</h3>
          <p class="graph-description">Bar diagram showing the number of articles of top 10 Medicinal Plants studied in cancer.</p>
          <div class="image-container">
            <img src="images/Medi_Plants.png" alt="Most Studied Medicinal Plants" class="graph-image" onclick="openModal(this.src)">

            <!-- <img src="images/Top_Medicinal_Plants_1_16_A4_600dpi.png" alt="Most Studied Medicinal Plants" class="graph-image" onclick="openModal(this.src)"> -->
          </div>
        </div>
        
        <!-- Graph 2: Pie Chart -->
        <div class="graph-card">
          <h3 class="graph-title">2. Medicinal Plants Reported in Cancer Curation</h3>
          <p class="graph-description">Distribution of plants reported versus unreported in cancer studies, highlighting research gaps in the field.</p>
          <div class="image-container">
            <img src="images/Reported2.png" alt="Cancer Research Coverage" class="graph-image" onclick="openModal(this.src)">
          </div>
        </div>
        
        <!-- Graph 3: Chord Diagram -->
        <div class="graph-card">
          <h3 class="graph-title">3. Classification of Articles based on System of Medicines</h3>
          <p class="graph-description">Classification of Indian medicinal plants by traditional medicine systems - Ayurveda, Unani, Siddha, Sowa Rigpa, and Homeopathy.</p>
          <div class="image-container">
            <img src="images/Croped_BROKEN_PIE.png" alt="Cancer Types and Plant Relationships" class="graph-image" onclick="openModal(this.src)">
          </div>
        </div>
        
        <!-- Graph 4: Medicine Systems -->
        <div class="graph-card">
          <h3 class="graph-title">4. Cancer Types</h3>
          <p class="graph-description">Distribution of Cancer Types studied.</p>
          <div class="image-container">
            <img src="images/Crop_CANCER_TYPES_2.png" alt="Medicine Systems Distribution" class="graph-image" onclick="openModal(this.src)">
          </div>
        </div>
        
        <!-- Graph 5: Study Types
        <div class="graph-card">
          <h3 class="graph-title">5. Research Study Types Analysis</h3>
          <p class="graph-description">UpSet plot showing the intersection and distribution of different study types (In Vitro, In Vivo, Clinical Trials, Reviews, In Silico) across cancer research.</p>
          <div class="image-container">
            <img src="images/study_types_upset.png" alt="Study Types Analysis" class="graph-image" onclick="openModal(this.src)">
          </div>
        </div> -->
        
      </div>
    </div>
    
    <!-- DATA TABLES TAB -->
    <div id="data-tables" class="tab-content">
      
      <?php
      // Function to read and display CSV files
      function displayCSVTable($filename, $tableNumber, $title, $description) {
          $filepath = "tables/" . $filename;
          
          if (!file_exists($filepath)) {
              echo "<div class='table-card'>";
              echo "<h3 class='table-title'>$title</h3>";
              echo "<p class='table-description'>$description</p>";
              echo "<p style='text-align:center; color:red;'>Error: File not found - $filename</p>";
              echo "</div>";
              return;
          }
          
          $file = fopen($filepath, 'r');
          
          if ($file === false) {
              echo "<div class='table-card'>";
              echo "<h3 class='table-title'>$title</h3>";
              echo "<p class='table-description'>$description</p>";
              echo "<p style='text-align:center; color:red;'>Error: Unable to read file - $filename</p>";
              echo "</div>";
              return;
          }
          
          echo "<div class='table-card'>";
          echo "<h3 class='table-title'>Table $tableNumber: $title</h3>";
          echo "<p class='table-description'>$description</p>";
          echo "<div style='text-align: center;'>";
          echo "<a href='$filepath' download class='download-btn'><i class='fas fa-download'></i>Download CSV</a>";
          echo "</div>";
          echo "<table class='data-table'>";
          
          // Read header row
          $headers = fgetcsv($file);
          if ($headers) {
              echo "<thead><tr>";
              foreach ($headers as $header) {
                  echo "<th>" . htmlspecialchars($header) . "</th>";
              }
              echo "</tr></thead>";
              
              // Read data rows
              echo "<tbody>";
              $rowCount = 0;
              $maxRows = 50; // Limit display to first 50 rows for performance
              
              while (($row = fgetcsv($file)) !== false && $rowCount < $maxRows) {
                  echo "<tr>";
                  foreach ($row as $cell) {
                      echo "<td>" . htmlspecialchars($cell) . "</td>";
                  }
                  echo "</tr>";
                  $rowCount++;
              }
              
              // Check if there are more rows
              if (!feof($file)) {
                  $totalRows = $rowCount;
                  while (fgetcsv($file) !== false) {
                      $totalRows++;
                  }
                  echo "<tr><td colspan='" . count($headers) . "' style='text-align:center; font-style:italic; padding:20px; background:#f0f0f0;'>";
                  echo "Showing first $maxRows rows of $totalRows total rows. Download CSV to view all data.";
                  echo "</td></tr>";
              }
              
              echo "</tbody>";
          }
          
          echo "</table>";
          echo "</div>";
          
          fclose($file);
      }
      
      // Display all 5 CSV tables
      displayCSVTable(
          "Plants.csv",
          "1",
          "Medicinal Plants",
          "Comprehensive listing of Top 10 medicinal and cancer-related research count."
      );
      
      displayCSVTable(
          "Cancer_ALL_20260107.csv",#"Cancer.csv",
          "2",
          "Cancer Types and Associated Studies",
          "Distribution of Top 10  cancer types covered in the database with study counts and primary mechanisms of action."
      );
            
      displayCSVTable(
          "Study_Types_Data.csv",#"Cancer.csv",
          "3",
          "Study Types and Associated Studies",
          "Distribution of all study types covered in the database with counts of literature studied."
      );
      
      
      // displayCSVTable(
      //     "table3.csv",
      //     "3",
      //     "Phytochemical Compounds Analysis",
      //     "Major phytochemical compounds identified in medicinal plants and their bioactive properties in cancer treatment."
      // );
      
      // displayCSVTable(
      //     "table4.csv",
      //     "4",
      //     "Study Design and Methodologies",
      //     "Classification of research studies by experimental design including in vitro, in vivo, and clinical trials."
      // );
      
      // displayCSVTable(
      //     "table5.csv",
      //     "5",
      //     "Geographic Distribution of Research",
      //     "Analysis of research contributions by country and region, highlighting global collaboration in cancer plant research."
      // );
      ?>
      
    </div>
    
  </div>
    <!-- edited on 20260105 6 pm -->
  <!-- Footer
  <footer>
    <div class="footer-content">
      <p>Computational Biology, Bioinformatics & Crosstalk Lab, ACTREC and Medical Oncology - Molecular Lab</p>
      <p>Tata Memorial Centre</p>
      <p>&copy; 2026 NatureCAN Database. All rights reserved.</p>
    </div>
  </footer> -->

</div>

<!-- Image Modal -->
<div id="imageModal" class="modal" onclick="closeModal()">
  <span class="close-modal">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

<!-- JavaScript for Tab Functionality and Image Modal -->
<script>
function openTab(evt, tabName) {
  // Hide all tab content
  var tabContent = document.getElementsByClassName("tab-content");
  for (var i = 0; i < tabContent.length; i++) {
    tabContent[i].classList.remove("active");
  }
  
  // Remove active class from all tab buttons
  var tabButtons = document.getElementsByClassName("tab-btn");
  for (var i = 0; i < tabButtons.length; i++) {
    tabButtons[i].classList.remove("active");
  }
  
  // Show the selected tab and mark button as active
  document.getElementById(tabName).classList.add("active");
  evt.currentTarget.classList.add("active");
  
  // Scroll to top of content
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Image Modal Functions
function openModal(imageSrc) {
  var modal = document.getElementById("imageModal");
  var modalImg = document.getElementById("modalImage");
  modal.style.display = "block";
  modalImg.src = imageSrc;
}

function closeModal() {
  document.getElementById("imageModal").style.display = "none";
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === "Escape") {
    closeModal();
  }
});
</script>

</body>
</html>


<?php include('footer.php'); ?>