<?php
/**
 * NatureCAN Shared Constants
 * Centralized configuration for table names, CDN versions, and SRI hashes.
 */

// Database table name - update here when importing new data
define('NATURECAN_TABLE', 'merged_output_d_20260122');

// Font Awesome CDN (v6.3.0)
define('FA_VERSION', '6.3.0');
define('FA_CSS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/' . FA_VERSION . '/css/all.min.css');
define('FA_CSS_SRI', 'sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==');

// jQuery CDN (v3.6.4)
define('JQUERY_VERSION', '3.6.4');
define('JQUERY_URL', 'https://ajax.googleapis.com/ajax/libs/jquery/' . JQUERY_VERSION . '/jquery.min.js');

// Bootstrap JS CDN (v3.4.1)
define('BOOTSTRAP_JS_URL', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js');
define('BOOTSTRAP_JS_SRI', 'sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxU');
