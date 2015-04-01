<?php
require_once 'core.inc.php';
require_once 'dbconnect.inc.php';
if(loggedin()) {
    require_once 'profile.inc.php';
} else {
    require_once 'Login.php';
}
?>