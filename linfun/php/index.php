<?
include "page_head.php";
$action=$_GET['action'];
 if ($action=="add_app") { include "../forms/app_add.php" ;};
 if ($action=="add_app_dwn") { include "../forms/app_add_dwn.php";};
 if ($action=="add_game") { include "../forms/game_add.php";};
 if ($action=="add_game_dwn") { include "../forms/game_add_dwn.php";};
 if ($action=="add_file") { include "../forms/file_add.php" ;};
?>