<?php
/*
 * fyr.php:
 * General purpose functions specific to FYR.
 * 
 * Copyright (c) 2004 UK Citizens Online Democracy. All rights reserved.
 * Email: francis@mysociety.org; WWW: http://www.mysociety.org
 *
 * $Id: fyr.php,v 1.3 2004-11-10 09:50:38 francis Exp $
 * 
 */

// Set location of configuration file in MYSOCIETY_FYR_CONFIG_FILE
require_once "../conf/general";

require_once "../../phplib/ratty.php";
require_once "../../phplib/utility.php";

/* fyr_rate_limit IMPORTANT
 * Invoke the rate limiter with the given IMPORTANT variables (e.g. postcode,
 * representative ID, etc.), as well as the script's URL and the calling IP
 * address, and return an error page if the request trips a rate limit;
 * otherwise do nothing. */
function fyr_rate_limit($important_vars) {
    $important_vars['IPADDR'] = $_SERVER['REMOTE_ADDR'];
    $important_vars['URL'] = invoked_url();

    if (!ratty_test($important_vars)) {
        $fyr_error_message = "Please limit your use of this website.";
        include "templates/generalerror.html";
        exit;
    }
}

?>
