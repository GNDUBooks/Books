<?php
require_once 'controller/core.inc.php';
require_once 'controller/dbconnect.inc.php';
if(loggedin()) {
    require_once 'profile.inc.php';
} else {
    require_once 'Login.php';
}
?>