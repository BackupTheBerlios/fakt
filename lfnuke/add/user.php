<?

include "../classes/user.php";
include "../includes/varchecks.php";


/* moves all varibles to to the user class */
  $newuser = new user($_POST['reg_nickname'], $_POST['reg_password'], $_POST['reg_email'],
  $_POST['reg_mess_net'], $_POST['reg_mess_id'], $_POST['reg_distro'],$this_date);

/* Checking if nickname is right */
  if ($newuser->nickname == "") 
     {give_error("301");};/*לא הכנסת כינוי */


/* Checks if the nickname is valid */

if (valid_nick($newuser->nickname)) {give_error("308");};

/* Checks if the passwords match*/
  if ($newuser->password == "") 
     {give_error("302");}/* לא הכנסת סיסמא */  
  else if ($_POST['reg_password2'] == "")
          {give_error("303");}/* לא הכנסת סיסמא שנייה */
  else if ($newuser->password <> $_POST['reg_password2'])
          {give_error("304");};/* סיסמאות אינן תואמות */

/* Checks if the password is valid */

   if (valid_password($newuser->password))
      {give_error("309");};
	  
/* chcking if mail address is good */	   
  if ($newuser->email == "") 
     {give_error("305");}; /* לא הכנסת כתובת דואל */
  if (valid_email($newuser->email)) 
     {give_error("307");}; /* כתובת שגוייה */
        
/* Checks if mess_net selected. if selected, checks if mess_id was entered*/
  if (valid_mess_id($newuser->mess_net, $newuser->mess_id)) 
  {give_error("306");}; /* בחרת תוכנת מסרים מידיים אך לא הכנסת את הזיהוי */
  
  ;
  
/* Checks if the nickname isn't already taken */

/* Send the user a email */
 
 /* recipients */
 $to  = $_POST['reg_mail'] . ", " ; // note the comma
 
 /* subject */
 $subject = "Thanks you for registering to linuXfun";
 
 /* message */
 
 $first_time_password=rand(10000, 99999);
 
 $message = '
    <html>
    <head>
      <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    </head>
   <body>
    <div style="text-align: center;"><font size="+1">Thank you for registering to linuXfun<br>
    </font>here is your first time password:<font size="+1"><br>'. $first_time_password .'
    <br>
    </font></div>
   </body>
    </html> ';
 
 /* To send HTML mail, you can set the Content-type header. */
 $headers  = "MIME-Version: 1.0\r\n";
 $headers .= "Content-type: text/html; charset=iso-8859-8\r\n";
 
 /* enter data into table */
 
 include "../includes/data.php";
 include "../configs/data.php";
 
 $data_link=data_start($data_host,$data_user,$data_pass);
 /* Checking if the nickname and email doesn't already exists in database */
  if (data_check($data_db, "users", "nickname", $newuser->nickname)) {give_error("310");};
  if (data_check($data_db, "users", "email", $newuser->email)) {give_error("311");};
  /* Continue in registering user */
 data_new_user($data_db,$data_link, $newuser->nickname,
               md5($newuser->password), $newuser->email, "ntba", $newuser->mess_net, $newuser->mess_id, $newuser->distro, "normal", md5($first_time_password), $this_date);
 echo mysql_error($data_link);
 data_stop($data_link);
 
 /* and now mail it */
 echo "We sent you a email to " . $_POST['reg_email'] . " with your first time password, check it";
 
 // mail($to, $subject, $message, $headers);
 
?>