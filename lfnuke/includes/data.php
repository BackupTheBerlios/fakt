<?
function data_start($data_host,$data_user,$data_pass)   {
   /* starts or stops database connection */
                    if ($mysql_link=mysql_connect($data_host,$data_user,$data_pass)) ;
                    else echo ("ERROR: cannot connect to MySQL server.");
		    return $mysql_link;                 }

function data_stop($data_host)
   {if ($do="stop") mysql_close($data_host /* Mysql link */);}

function data_new_user ($data_db,$data_link, $nickname, $password, $email, $mode, $mess_net, $mess_id, $distro, $status, $first_time_password, $add_date) 
      /* inserts data into table */
      {
      mysql_query("USE $data_db");
      mysql_query("INSERT INTO `users` ( `id` , `points` , `mode` , `nickname` , `password` , `email` , `mess_net` , `mess_id` , `distro` , `status` , `add_date` , `first_time_password` ) 
VALUES ( '', '0', '$mode', '$nickname', '$password', '$email', '$mess_net', '$mess_id', '$distro', '$status', '$add_date', '$first_time_password');");  
      }

function data_check($data_db, $table, $what, $get)
      { mysql_query("USE $data_db");
        mysql_select_db("$data_db");
	$query=mysql_query("SELECT * FROM $table WHERE $what LIKE '$get';");
	$result=mysql_fetch_array($query);
	if ($result["$what"]<>"") return true;
      }



?>