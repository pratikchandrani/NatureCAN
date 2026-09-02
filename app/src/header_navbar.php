<?php
// header_navbar.php - outputs only the <header> element with its styles
require_once __DIR__ . '/constants.php';
?>
    <link rel="stylesheet" href="<?php echo FA_CSS_URL; ?>" integrity="<?php echo FA_CSS_SRI; ?>" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .main-header {
            background: linear-gradient(135deg, rgba(32, 85, 138, 0.95) 0%, rgba(26, 69, 112, 0.95) 100%),
                        url('images/generated-image.png') center/cover;
            background-blend-mode: overlay;
            padding: 20px 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        /* Logo styles */
        .header-logos {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header-logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8))
                    drop-shadow(0 0 15px rgba(255, 255, 255, 0.6))
                    drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .header-logo img:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.9))
                    drop-shadow(0 0 20px rgba(255, 255, 255, 0.7))
                    drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
        }

        .header-branding {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .header-branding:hover {
            opacity: 0.9;
        }

        .header-branding:hover .header-title {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3),
                        0 0 20px rgba(255, 255, 255, 0.5);
        }

        .header-title {
            font-family: 'Verdana', sans-serif;
            font-size: 42px;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-family: 'Verdana', sans-serif;
            font-size: 16px;
            color: #e0f2ff;
            font-style: italic;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin-left: 2px;
        }

        .header-navigation {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-right: 20px;
        }

        .nav-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            min-width: 120px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-family: 'Verdana', sans-serif;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .nav-button.active {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .nav-button i {
            font-size: 16px;
        }

        .header-search {
            position: relative;
            max-width: 350px;
        }

        .search-input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Verdana', sans-serif;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .search-button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #20558a 0%, #1a4570 100%);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
        }

        .search-button:hover {
            background: linear-gradient(135deg, #1a4570 0%, #20558a 100%);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .header-container {
                flex-wrap: wrap;
                gap: 20px;
            }

            .header-search {
                order: 3;
                width: 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-header {
                padding: 15px 20px;
            }

            .header-title {
                font-size: 32px;
            }

            .header-subtitle {
                font-size: 14px;
            }

            .header-navigation {
                flex-wrap: wrap;
                gap: 10px;
            }

            .nav-button {
                padding: 10px 18px;
                font-size: 13px;
            }

            .header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-logo img {
                height: 48px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 28px;
            }

            .header-subtitle {
                font-size: 12px;
            }

            .nav-button {
                padding: 8px 14px;
                font-size: 12px;
            }

            .nav-button i {
                font-size: 14px;
            }

            .header-logo img {
                height: 38px;
            }

            .header-logos {
                gap: 10px;
            }
        }
    </style>

    <header class="main-header">
        <div class="header-container">
            <div class="header-left">
                <div class="header-logos">
                    <div class="header-logo">
                        <a href="https://actrec.gov.in/home" target="_blank">
                            <img src="images/ACTREC_logo1.png" alt="ACTREC Logo">
                        </a>
                    </div>

                    <div class="header-logo">
                        <a href="https://tmc.gov.in/" target="_blank">
                            <img src="images/TMC_Logo1.png" alt="TMC Logo">
                        </a>
                    </div>
                </div>

                <a href="homepage.php" class="header-branding">
                    <h1 class="header-title">NatureCAN</h1>
                    <p class="header-subtitle">Database of evidence-based Medicinal Plants in Cancer Care</p>
                </a>

                <nav class="header-navigation">
                    <a href="homepage.php" class="nav-button <?php echo (basename($_SERVER['PHP_SELF']) == 'homepage.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="statistics.php" class="nav-button <?php echo (basename($_SERVER['PHP_SELF']) == 'statistics.php') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistics</span>
                    </a>
                    <a href="nc_trial_table.php" class="nav-button <?php echo (basename($_SERVER['PHP_SELF']) == 'nc_trial_table.php') ? 'active' : ''; ?>">
                        <i class="fas fa-table"></i>
                        <span>NatureCAN DB</span>
                    </a>
                    <a href="about.php" class="nav-button <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">
                        <i class="fas fa-info-circle"></i>
                        <span>About</span>
                    </a>
                </nav>
            </div>

            <div class="header-search">
                <form action="nc_trial_table.php" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Search in NatureCAN..." aria-label="Search">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>
