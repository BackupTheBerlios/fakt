<?

include "../classes/file.php";
include "../classes/game.php";
include "../includes/uploadNcheck.php";
include "../includes/varchecks.php";

/* Uses the Game Class */

$thisgame = new 
game($_POST['game_name'],$_POST['game_desc'],"x",$_POST['game_home'],$_POST['game_cat'],$game_default_points,$game_default_points,$game_default_points,$game_default_points,$game_default_points, $this_date );

/* Check varibles */

if (isempty($thisgame->name))
   {give_error("201");};// חסר שם המשחק
   
if (isempty($thisgame->desc))
   {give_error("202");};  //חסר תיאור המשחק

if ($game_pic_file=$HTTP_POST_FILES['game_pic']['name']=="") 
   {give_error("203");}; //חסרה תמונת המשחק

if (isempty($thisgame->home))
   {give_error("204");};  //חסר אתר המשחק
   
if (catempty($thisgame->category))
   {give_error("205");}; //חסרה קטגוריית המשחק

/* Set game picture varibles for checking */
 

$game_file = new ($game_pic_tmp_file=$HTTP_POST_FILES['game_pic']['tmp_name'],$game_pic_file=$HTTP_POST_FILES['game_pic']['name'],$game_pic_file_size=$HTTP_POST_FILES['game_pic']['size'],$game_pic_file_type=$HTTP_POST_FILES['game_pic']['type'],$game_pic_type="games",$game_filename=$_FILES['uploaded']['name']);



/* Checking */
if (valid_nick($thisgame->name))
   {give_error("206");};

if (valid_filename($thisgame->name))
   {give_error("207");};   


    
/* Uploading... */
$game_pic_max_size=100000;

uploadNcheck($game_file->temp, $game_file->name, $game_pic_max_size, $game_file->type, $game_file->category, $game_file->size,"picture");
?>