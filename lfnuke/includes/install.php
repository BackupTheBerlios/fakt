<?
function install($now)
   {if ($now="now")
       {include "../configs/data.php";
        if ($mysql_link=mysql_connect($data_host,$data_user,$data_pass)) 
	{
		/* adding new table named 'users' */
		mysql_query("USE $data_db");
		mysql_query("CREATE TABLE `users` (`id` BIGINT NOT NULL, `points` INT DEFAULT '0' NOT NULL ,
 		`mode` VARCHAR( 4 ) DEFAULT 'ntba' NOT NULL ,
 		`nickname` VARCHAR( 15 ) NOT NULL ,
 		`password` VARCHAR( 15 ) NOT NULL ,
 		`email` VARCHAR( 40 ) NOT NULL ,
 		`mess_net` VARCHAR( 10 ) NOT NULL ,
 		`mess_id` VARCHAR( 30 ) NOT NULL ,
 		`distro` VARCHAR( 20 ) NOT NULL ,
 		`status` VARCHAR( 10 ) DEFAULT 'normal' NOT NULL ,
 		`add_date` DATE NOT NULL ,
 		`first_time_password` VARCHAR( 6 ) NOT NULL )");
		mysql_query("ALTER TABLE `users` ADD PRIMARY KEY ( `id` )");
		mysql_query("ALTER TABLE `users` ADD INDEX ( `id` )");
		mysql_query("ALTER TABLE `users` CHANGE `id` `id` BIGINT( 20 ) DEFAULT '0' NOT NULL 
		AUTO_INCREMENT;");
	        echo mysql_error($mysql_link);
		mysql_close($mysql_link);
		
	}	
           else echo ("ERROR: cannot connect to MySQL server.");	
	}
    }
?>