<?php

require_once ("../views/common_ui.php");
view_common_includes("../");
$db = new DB();

if (isset($_GET["type"]) && isset($_GET["id"])) {

    if ($_GET["type"] == "dismiss") {

        $db->updateAlertDismiss($_GET["id"]);
        header("Location: https://seniordevteam1.in/views/alerts_ui.php");
        exit;

    } // Ends type if

    header("Location: https://seniordevteam1.in/views/alerts_ui.php");
    exit;

} else {

    header("Location: https://seniordevteam1.in/views/alerts_ui.php");
    exit;

} // Ends GET if