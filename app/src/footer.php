<style>
/* Footer Styles */
footer {
  background: url("images/generated-image.png") no-repeat center center;
  background-size: cover;
  color: #ffffff;
  padding: 30px 20px;
  margin-top: 40px;
  text-align: center;
  position: relative;
}

footer::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(58, 125, 68, 0.6);
}

footer * {
  position: relative;
  z-index: 1;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
}

.footer-title {
  font-size: 0.9rem;
  margin-bottom: 8px;
  font-weight: 500;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}

.footer-subtitle {
  font-size: 0.85rem;
  margin-bottom: 20px;
  opacity: 0.9;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}

.footer-social {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-bottom: 20px;
}

.social-icon {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  text-decoration: none;
  transition: 0.3s;
  font-size: 1.2rem;
}

.social-icon:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

.footer-copyright {
  font-size: 0.8rem;
  opacity: 0.8;
  margin-top: 15px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}

.footer-visitors {
  font-size: 0.8rem;
  opacity: 0.85;
  margin-top: 8px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
}
</style>

<?php
require_once __DIR__ . '/constants.php';
if (!defined('FONTAWESOME_LOADED')):
?>
<link rel="stylesheet" href="<?php echo FA_CSS_URL; ?>" integrity="<?php echo FA_CSS_SRI; ?>" crossorigin="anonymous">
<?php define('FONTAWESOME_LOADED', true); endif; ?>

<footer>
  <div class="footer-content">
    <div class="footer-title"><a href="https://pratikchandrani.github.io" target="_blank" style="color:inherit;text-decoration:none;">Computational Biology, Bioinformatics &amp; Crosstalk Lab</a></div>
    <div class="footer-subtitle">Advanced Centre for Treatment, Research and Education in Cancer</div>
    <div class="footer-subtitle">Tata Memorial Centre</div>

    <div class="footer-social">
      <a href="https://actrec.gov.in/home" class="social-icon" aria-label="ACTREC" target="_blank" title="ACTREC Website">
        <i class="fas fa-hospital"></i>
      </a>
      <a href="https://tmc.gov.in/" class="social-icon" aria-label="TMC" target="_blank" title="Tata Memorial Centre">
        <i class="fas fa-building"></i>
      </a>
      <a href="https://pratikchandrani.github.io" class="social-icon" aria-label="Computational Lab" target="_blank" title="Chandrani lab">
        <i class="fas fa-laptop-code"></i>
      </a>
      <a href="mailto:pchandrani@actrec.gov.in?subject=Inquiry%20about%20NatureCAN" class="social-icon" aria-label="Email" title="Contact via Email">
        <i class="fas fa-envelope"></i>
      </a>
    </div>

    <div class="footer-copyright">&copy; 2026 Chandrani lab, ACTREC</div>

    <?php
    // Visitor counter
    if (isset($conn) && $conn) {
        // Create table if not exists
        mysqli_query($conn, "CREATE TABLE IF NOT EXISTS visitor_counter (
            id INT AUTO_INCREMENT PRIMARY KEY,
            visitor_ip VARCHAR(45) NOT NULL,
            visit_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_visit (visitor_ip, visit_date)
        )");

        // Record this visit (one per IP per day)
        $visitor_ip = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $today = date('Y-m-d');
        mysqli_query($conn, "INSERT IGNORE INTO visitor_counter (visitor_ip, visit_date) VALUES ('$visitor_ip', '$today')");

        // Get total unique visitors
        $count_result = mysqli_query($conn, "SELECT COUNT(DISTINCT visitor_ip) AS total FROM visitor_counter");
        $visitor_count = 0;
        if ($count_result) {
            $row = mysqli_fetch_assoc($count_result);
            $visitor_count = $row['total'];
        }
    ?>
    <div class="footer-visitors">
      <i class="fas fa-eye"></i> Visitors: <?php echo number_format($visitor_count); ?>
    </div>
    <?php } ?>
  </div>
</footer>
</body>
</html>
