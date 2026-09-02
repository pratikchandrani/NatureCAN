<?php
include('header_navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About | NatureCAN</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

<style>
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
}

/* FIXED BACKGROUND - Stays in place during scroll */
.fixed-pattern-bg {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 0;
  background-image: url('image5.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  filter: brightness(0.9);
}

.fixed-pattern-bg::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(27, 94, 32, 0.25), rgba(46, 125, 50, 0.25));
}

/* All Content Container */
.content-wrapper {
  position: relative;
  z-index: 10;
  min-height: 100vh;
}

/* Page Header */
.page-header {
  text-align: center;
  padding: 40px 20px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  margin: 30px auto;
  max-width: 1200px;
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

/* Content Container */
.content-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

/* Sections Container */
.sections-container {
  max-width: 1200px;
  width: 100%;
  margin: 30px auto 50px auto;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
}

/* Individual Section */
.section {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(10px);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  transition: all 0.4s;
  border-top: 5px solid var(--primary);
  display: flex;
  flex-direction: column;
}

.section:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(58, 125, 68, 0.25);
}

.section h2 {
  font-size: 1.5rem;
  color: var(--primary);
  margin-bottom: 15px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.section p {
  font-size: 0.95rem;
  line-height: 1.7;
  color: #555;
}

/* Icons */
.section-icon {
  font-size: 2rem;
  color: var(--secondary);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .sections-container {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .page-header h1 {
    font-size: 2rem;
  }
  
  .section h2 {
    font-size: 1.3rem;
  }
}

@media (max-width: 768px) {
  .page-header {
    padding: 30px 15px;
  }
  
  .page-header h1 {
    font-size: 1.8rem;
  }
  
  .section {
    padding: 20px;
  }
}
</style>
</head>

<body>

<!-- FIXED BACKGROUND -->
<div class="fixed-pattern-bg"></div>

<!-- MAIN CONTENT -->
<div class="content-wrapper">
  
  <!-- Page Header -->
  <div class="page-header">
    <h1>About NatureCAN</h1>
    <p>An integrated knowledgebase of medicinal plants and cancer research</p>
  </div>
  
  <!-- Content Container -->
  <div class="content-container">
    
    <!-- Three Column Layout -->
    <div class="sections-container">
      
      <!-- SECTION 1 -->
      <div class="section">
        <h2><i class="fas fa-leaf section-icon"></i> What is NatureCAN?</h2>
        <p>
          NatureCAN is a curated, evidence-based database focused on medicinal plants
          and their roles in cancer prevention and treatment. It integrates experimental,
          preclinical, and clinical research data into a single searchable platform.
        </p>
      </div>

      <!-- SECTION 2 -->
      <div class="section">
        <h2><i class="fas fa-database section-icon"></i> Database Contents</h2>
        <p>
          NatureCAN includes detailed information on medicinal plants, associated
          cancer types, study models, experimental techniques, and observed
          therapeutic effects.
        </p>
      </div>

      <!-- SECTION 3 -->
      <div class="section">
        <h2><i class="fas fa-bullseye section-icon"></i> Mission & Future</h2>
        <p>
          Our mission is to bridge traditional medicinal knowledge with modern
          cancer research through computational integration and systematic analysis.
        </p>
      </div>

    </div>
  </div>

</div>

</body>
</html>

<?php include('footer.php'); ?>