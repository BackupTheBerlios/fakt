<html>
<head>
  <meta content="text/html; charset=windows-1255" http-equiv="content-type">
  <body dir=rtl>
<?

include 'config.php';
include 'classes/user.php';

/* Moving all POSTs to a varibles And setting date, points and edit-mode*/

$new_user = new user ($_POST['reg_nickname'], $_POST['reg_password'], $_POST['reg_email'], $_POST['reg_mess_net'], $_POST['reg_mess_id'], $_POST['reg_distro']);

/* Restarting all objects */
/* מאתחל נתונים  */
  
  $backtoreg="0"; 
  $reg_nickname_msg=""; /* reseting nickname message */
  $password_msg="";     /* reseting password message */
  $password2_msg="";    /* reseting password2 message */
  $email_msg="";        /* reseting nickname message */
  $pass_match_msg="";   /* reseting nickname message */
      
/* Checking if all fields are correct */
/* בדיקה האם כל המשתנים הוכנסו */
/* $backtoreg check if backing to the register form is needed,  */

/* Checking if nickname is right */
 
 if ($reg_nickname] == "") 
    {$reg_nickname_msg="לא הכנסת כינוי<br>"; $backtoreg="1";};
 
/* Checking if password is good */
 if ($reg_password] == "") 
    {$password_msg ="לא הכנסת סיסמא<br>"; $backtoreg="1";}
    else if ($reg_password2] == "")
            {$password2_msg ="לא הכנסת סיסמא שנייה<br>"; $backtoreg="1"; }
    else if (($reg_password) <> ($reg_password2))
            {$pass_match_msg="הסיסמאות אינן תואמות<br>"; $backtoreg="1"; };
      
/* chcking if mail address is good */	   
 if ($reg_email == "") 
      { $email_msg="לא הכנסת כתובת דואל<br>"; $backtoreg="1"; };
 
/* Checking if there is need to come back to the register page */

if ($backtoreg == "1") {include('user.php'); exit();};


/* Connecting to MySQL server */

$mysql_link=mysql_connect($sql_host,$sql_user,$sql_pass)
or die("ERROR: cannot connect to MySQL server.");
 /* Checks if email exist on database */
 
 /*if mysql_query(SELECT 1 FROM table WHERE email = $reg_email) 
       {echo "your email does not exist"}
  else {echo "your email does exist"}; */
  
/* Checks if linfun database existed, and if not, creating one */

if (mysql_select_db($sql_db,$mysql_link))
     ;
  else 
 
  if (mysql_query("CREATE DATABASE $sql_db")) 
          { echo "$sql_db Database created successfully<br>";}
     else { echo "Error creating $sql_db database: " . mysql_error() . "<br>"; }

/* Inserting data into table if exist*/

  mysql_query("CREATE TABLE user ( id INT, reg_date BIGINT , points INT, edit_mode CHAR(1), nickname VARCHAR(15), password VARCHAR(15), reg_email VARCHAR(15), reg_mess_net VARCHAR(15), reg_mess_id VARCHAR(15), reg_distro VARCHAR(15))");
  
  mysql_query ("INSERT INTO `user` ( `user_id` , `reg_date` , `points` , `edit_mode` , `nickname` , `password` , `email` , `mess_net` , `mess_id` , `distro` ) VALUES ( '1', $points , $edit_mode, $edit_mode, $reg_nickname, $reg_password, $reg_email, $reg_mess_net, $reg_mess_id, $reg_distro)");
  
/* Closing the connection to mysql */
mysql_close($mysql_link);


/* Sending user back to user.php */
$reg_nickname_msg="נרשמת כבר, עכשיו נסה להכנס למערכת<br>";

/* For checking enable the next line and disable the line after*/
echo date(dmY,$reg_date) . ", $points, $edit_mode, $reg_nickname, $reg_password, $reg_email, $reg_mess_net, $reg_mess_id, $reg_distro";

/* include ("user.php"); */
  

  ?>
</body>
</html>