<HTML>
<HEAD>
<?PHP
/*
  | System administration script of FAKT guides system
  | This script will generate the needed tables for the guides system
  |
  | FAKT Free Authoring Knowledge & Thinking
  | Copyright: Helicon technologies LTD. 2003
  | The FAKT system is distributed under the GNU public license
  |
  | This file is part of FAKT system
  |
  | FAKT is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | FAKT is distributed in the hope that it will be useful,
  | but WITHOUT ANY WARRANTY; without even the implied warranty of
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  | GNU General Public License for more details.
  |
  | You should have received a copy of the GNU General Public License
  | along with the software; if not, write to the Free Software
  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  |
  */
include('config.inc.php');
include('glob.inc');
include('common.inc');
?>
<meta http-equiv=Content-Type content="text/html; charset=windows-UTF-8">
<?PHP print "<TITLE>guides tables creation utility</TITLE>\n"; ?>
<?PHP

print "</HEAD>\n";
print "<BODY>\n";

print "<CENTER><H1>FAKT Guides table creation utility</H1></CENTER>\n";

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");
/*
 | First check that main tables, login and mainlist are present
 */
$query = "SHOW TABLES";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}
$titlesexist = 0;
$guidesexist = 0;
print "Searching for main tables...<BR>\n";
while($line = mysql_fetch_array($result, MYSQL_NUM)) {
  if($line[0] == 'login')
    $loginexist = 1;
  if($line[0] == 'titles')
    $titlesexist = 1;
  if($line[0] == 'guides')
    $guidesexist = 1;

}
if(!$loginexist) {
  print "Table login does not exist...<BR>\n";
  $query = "CREATE TABLE login (";
  $query .= "name VARCHAR(50), ";
  $query .= "fullname VARCHAR(90), ";
  $query .= "email VARCHAR(60), ";
  $query .= "password VARCHAR(30), ";
  $query .= "lastonline TIMESTAMP, ";
  $query .= "pubemail VARCHAR(60), ";
  $query .= "web VARCHAR(80), ";
  $query .= "messangernum VARCHAR(15), ";
  $query .= "messangersoft VARCHAR(15), ";
  $query .= "birthdate DATE, ";
  $query .= "sex enum(\"female\", \"male\"), ";
  $query .= "martial VARCHAR(30), ";
  $query .= "occupation VARCHAR(60), ";
  $query .= "interest VARCHAR(120), ";
  $query .= "signature VARCHAR(255), ";
  $query .= "comments TEXT, ";
  $query .= "picture VARCHAR(60) ";
  $query .= ")";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "Table login created.<BR>\n";
}
if(!$titlesexist) {
  print "Titles table does not exist...<BR>\n";
  $query = "CREATE TABLE titles (";
  $query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
  $query .= "grp VARCHAR(60), ";
  $query .= "name VARCHAR(90), ";
  $query .= "publish INTEGER, ";
  $query .= "editors VARCHAR(255), ";
  $query .= "creation DATE, ";
  $query .= "modification DATE ";
  $query .= ")";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "Titles table created.<BR>\n";
}
if(!$guidesexist) {
  print "Guides table does not exist...<BR>\n";
  $query = "CREATE TABLE guides (";
  $query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
  $query .= "id INTEGER UNSIGNED, ";
  $query .= "ancestor INTEGER UNSIGNED, ";
  $query .= "header VARCHAR(120), ";
  $query .= "contents TEXT, ";
  $query .= "picture VARCHAR(70)";
  $query .=")";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "Guides table created.<BR>\n";
}
