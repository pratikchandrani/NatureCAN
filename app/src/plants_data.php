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
<title>Medicinal Plants Database | NatureCAN</title>
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
  padding: 30px 20px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  margin: 20px auto;
  max-width: 1400px;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
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

/* Search Container */
.search-container {
  max-width: 500px;
  margin: 30px auto;
  padding: 0 20px;
}

.search-box {
  position: relative;
  width: 100%;
}

.search-input {
  width: 100%;
  padding: 15px 50px 15px 20px;
  font-size: 1rem;
  border: 2px solid var(--primary);
  border-radius: 50px;
  outline: none;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.search-input:focus {
  border-color: var(--secondary);
  box-shadow: 0 6px 20px rgba(58, 125, 68, 0.2);
  transform: translateY(-2px);
}

.search-icon {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--primary);
  font-size: 1.2rem;
  pointer-events: none;
}

.search-results-info {
  text-align: center;
  margin-top: 15px;
  font-size: 0.95rem;
  color: #666;
  font-weight: 500;
}

/* Plants Container */
.plants-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

/* Table Card */
.table-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  margin-bottom: 30px;
  transition: all 0.4s;
  overflow-x: auto;
}

.table-card:hover {
  box-shadow: 0 15px 40px rgba(58, 125, 68, 0.25);
}

/* Data Table */
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
  position: sticky;
  top: 0;
  z-index: 10;
}

.data-table th {
  padding: 12px 15px;
  text-align: left;
  font-weight: 600;
  font-size: 1rem;
  white-space: nowrap;
}

.data-table td {
  padding: 10px 15px;
  border-bottom: 1px solid #e0e0e0;
  font-size: 0.95rem;
}

.data-table tbody tr {
  transition: all 0.3s;
}

.data-table tbody tr:hover {
  background: rgba(107, 191, 89, 0.1);
  transform: scale(1.005);
}

.data-table tbody tr:nth-child(even) {
  background: rgba(246, 255, 245, 0.5);
}

/* Plant Name Link */
.plant-link {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.plant-link:hover {
  color: var(--secondary);
  transform: translateX(5px);
}

.plant-link i {
  font-size: 0.9rem;
  opacity: 0;
  transition: opacity 0.3s;
}

.plant-link:hover i {
  opacity: 1;
}

/* Article Count Badge */
.article-count {
  background: var(--primary);
  color: white;
  padding: 4px 12px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 0.9rem;
  display: inline-block;
  min-width: 45px;
  text-align: center;
  box-shadow: 0 2px 5px rgba(58, 125, 68, 0.2);
}

/* No Results Message */
.no-results {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 1.1rem;
}

.no-results i {
  font-size: 4rem;
  color: var(--primary);
  margin-bottom: 20px;
  opacity: 0.3;
}

/* Download Button */
.download-btn {
  display: inline-block;
  margin-bottom: 20px;
  padding: 12px 24px;
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

/* Loading Animation */
.loading {
  text-align: center;
  padding: 40px;
  color: var(--primary);
  font-size: 1.2rem;
}

.loading i {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
  .page-header h1 {
    font-size: 2rem;
  }
  
  .search-container {
    max-width: 100%;
  }
  
  .search-input {
    font-size: 1rem;
    padding: 15px 50px 15px 20px;
  }
  
  .data-table {
    font-size: 0.9rem;
  }
  
  .data-table th, .data-table td {
    padding: 10px 8px;
  }
  
  .article-count {
    font-size: 0.85rem;
    padding: 5px 12px;
  }
}

@media (max-width: 480px) {
  .page-header {
    padding: 30px 15px;
  }
  
  .page-header h1 {
    font-size: 1.6rem;
  }
  
  .search-input {
    font-size: 0.95rem;
  }
  
  .table-card {
    padding: 20px 15px;
  }
}
</style>
</head>
<body>

<div class="content-wrapper">
  
  <!-- Page Header -->
  <div class="page-header">
    <h1><i class="fas fa-leaf"></i> Medicinal Plants Database</h1>
    <p>Explore comprehensive information about medicinal plants and their cancer research articles. Click on any plant name to view its phytochemical compounds in IMPPAT database.</p>
  </div>
  
  <!-- Search Container -->
  <div class="search-container">
    <div class="search-box">
      <input 
        type="text" 
        id="plantSearch" 
        class="search-input" 
        placeholder="Search for medicinal plants..." 
        autocomplete="off"
      >
      <i class="fas fa-search search-icon"></i>
    </div>
    <div class="search-results-info" id="resultsInfo"></div>
  </div>
  
  <!-- Plants Container -->
  <div class="plants-container">
    <div class="table-card">
      <div style="text-align: center;">
        <a href="tables/plants_data1.csv" download class="download-btn">
          <i class="fas fa-download"></i>Download Complete Data (CSV)
        </a>
      </div>
      
      <table class="data-table" id="plantsTable">
        <thead>
          <tr>
            <th style="width: 10%;">Sr. No.</th>
            <th style="width: 60%;">Plant Name</th>
            <th style="width: 30%;">Number of Articles</th>
          </tr>
        </thead>
        <tbody id="plantsTableBody">
          <?php
          $filepath = "tables/plants_data1.csv";
          
          if (!file_exists($filepath)) {
              echo "<tr><td colspan='3' style='text-align:center; color:red; padding:40px;'>";
              echo "<i class='fas fa-exclamation-triangle' style='font-size:3rem; display:block; margin-bottom:15px;'></i>";
              echo "Error: plants_data1.csv file not found in tables/ folder</td></tr>";
          } else {
              $file = fopen($filepath, 'r');
              
              if ($file === false) {
                  echo "<tr><td colspan='3' style='text-align:center; color:red; padding:40px;'>Error: Unable to read plants_data.csv file</td></tr>";
              } else {
                  // Skip header row
                  $headers = fgetcsv($file);
                  
                  $serialNo = 1;
                  while (($row = fgetcsv($file)) !== false) {
                      // Assuming CSV format: Plant Name, Article Count
                      if (count($row) >= 2) {
                          $plantName = trim($row[0]);
                          $articleCount = trim($row[1]);
                          
                          // Create IMPPAT URL with URL encoding
                          $imppat_url = "https://cb.imsc.res.in/imppat/phytochemical/" . urlencode($plantName);
                          
                          echo "<tr data-plant-name='" . htmlspecialchars(strtolower($plantName)) . "'>";
                          echo "<td>" . $serialNo . "</td>";
                          echo "<td>";
                          echo "<a href='" . htmlspecialchars($imppat_url) . "' target='_blank' class='plant-link'>";
                          echo "<span>" . htmlspecialchars($plantName) . "</span>";
                          echo "<i class='fas fa-external-link-alt'></i>";
                          echo "</a>";
                          echo "</td>";
                          echo "<td><span class='article-count'>" . htmlspecialchars($articleCount) . "</span></td>";
                          echo "</tr>";
                          
                          $serialNo++;
                      }
                  }
                  
                  fclose($file);
                  
                  if ($serialNo == 1) {
                      echo "<tr><td colspan='3' style='text-align:center; padding:40px;'>";
                      echo "<i class='fas fa-info-circle' style='font-size:3rem; display:block; margin-bottom:15px; color:var(--primary);'></i>";
                      echo "No plant data available in the CSV file</td></tr>";
                  }
              }
          }
          ?>
        </tbody>
      </table>
      
      <!-- No Results Message (Hidden by default) -->
      <div id="noResults" class="no-results" style="display: none;">
        <i class="fas fa-search-minus"></i>
        <p>No plants found matching your search.</p>
        <p style="font-size: 0.9rem; margin-top: 10px;">Try different keywords or clear the search.</p>
      </div>
      
    </div>
  </div>

</div>

<!-- JavaScript for Search Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('plantSearch');
  const tableBody = document.getElementById('plantsTableBody');
  const noResults = document.getElementById('noResults');
  const resultsInfo = document.getElementById('resultsInfo');
  const allRows = Array.from(tableBody.getElementsByTagName('tr'));
  const totalPlants = allRows.length;
  
  // Update results info on page load
  updateResultsInfo(totalPlants, totalPlants);
  
  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    let visibleCount = 0;
    
    if (searchTerm === '') {
      // Show all rows if search is empty
      allRows.forEach(row => {
        row.style.display = '';
        visibleCount++;
      });
      noResults.style.display = 'none';
      tableBody.style.display = '';
      updateResultsInfo(totalPlants, totalPlants);
    } else {
      // Filter rows based on search term
      allRows.forEach(row => {
        const plantName = row.getAttribute('data-plant-name');
        if (plantName && plantName.includes(searchTerm)) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
      
      // Show/hide no results message
      if (visibleCount === 0) {
        noResults.style.display = 'block';
        tableBody.style.display = 'none';
      } else {
        noResults.style.display = 'none';
        tableBody.style.display = '';
      }
      
      updateResultsInfo(visibleCount, totalPlants);
    }
    
    // Renumber visible rows
    renumberRows();
  });
  
  function renumberRows() {
    let serialNo = 1;
    allRows.forEach(row => {
      if (row.style.display !== 'none') {
        row.cells[0].textContent = serialNo;
        serialNo++;
      }
    });
  }
  
  function updateResultsInfo(visible, total) {
    if (searchInput.value.trim() === '') {
      resultsInfo.innerHTML = `Showing <strong>${total}</strong> medicinal plants`;
    } else {
      resultsInfo.innerHTML = `Found <strong>${visible}</strong> of <strong>${total}</strong> plants`;
    }
  }
  
  // Add smooth scroll to top on page load
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>

</body>
</html>

<?php include('footer.php'); ?>