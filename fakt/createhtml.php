<HTML>
<HEAD>
<?PHP
/*
  | Show guides script of YAFS guides system
  |
  | YAFS copyright: Helicon technologies LTD. 2003
  | YAFS is distributed under the GNU public license
  |
  | This file is part of YAFS (Yet Another Forums System).
  |
  | YAFS is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | YAFS is distributed in the hope that it will be useful,
  | but WITHOUT ANY WARRANTY; without even the implied warranty of
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  | GNU General Public License for more details.
  |
  | You should have received a copy of the GNU General Public License
  | along with the software; if not, write to the Free Software
  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  |
  */
include('config.inc');
include('glob.inc');
include('common.inc');

$htmldir = "$home/htmlguides";
$title = "????? ???????";

?>
<TITLE> ????? ??????? ????? ???? HTML</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">

<?PHP

$id = $_GET['id'];

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

/* Auxliary functions */

function GetGuideName($id) {
  global $title;
  global $grp;
  global $editors;
  global $moddate;

  $query = "SELECT * FROM titles WHERE num=$id";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }

  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $title = $line['name'];
  $grp = $line['grp'];
  $editors = $line['editors'];
  $moddate = $line['modification'];
  $moddate = FormatDate($moddate);
}

/*
 | Recursive function to show all sections in guide
 */
function ShowGuide($id, $ancestor) {
  global $last;
  global $name;
  global $editors;
  global $userfiles;

  $query = "SELECT * from guides WHERE id=$id AND ancestor=$ancestor";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  if(mysql_num_rows($result) == 0) {
    print "</TABLE>\n";
    return 0;	/* end of recursive call */
  }
  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $num = $line['num'];
  $last = $num;
  $header = $line['header'];
  $contents = $line['contents'];
  $picture = $line['picture'];
  $header = SpecialCodes($header);
  $contents = SpecialCodes($contents);

  if($ancestor == 0) { /* this is first time so start table */
    print "<TABLE dir=RTL border=0 width=100%>\n";
  }
  print "<TR><TD>\n";
  print "<A HREF=javascript:void() onclick=\"blocking('s$num', 'h$num')\">$header</A><BR>\n";

  print "<TR><TD>\n";

  print "<DIV class=para id=s$num>\n";
  print "<TABLE dir=RTL width=100%><TR><TD valign=top>\n";
  DisplayContents($contents);
  print "<BR>\n";

  if(!empty($picture)) {
    print "<TD valign=center align=center>\n";
    $ext = GetExt($picture);
    if(IsImg($ext)) {
      $s = getimagesize("$userfiles/$picture");
      $w = $s[0];
      $ow = $w + 35;
      $h = $s[1];
      $oh = $h + 35;
      if($w > 100) {
	$ar = $h/$w;
	$h = 200*$ar;
	$w = 200;
      }
      print "<A HREF=\"#\" onclick=\"javascript:window.open(";
      print "'$userfiles/$picture', 'Picture', 'height=$oh, width=$ow scrollbars=yes resizable=yes')\">";
      print "<IMG SRC=$userfiles/$picture width=$w height=$h border=0>";
      print "</A><BR><BR>\n";
    }
    else {
      print "<TD><A HREF=$userfiles/$picture target=_blank>";
      print "????? ???? ";
      print "$ext</A><BR>\n";
    }

  }
  print "</TABLE>\n";

  print "</DIV>\n";
  ShowGuide($id, $num);
}

/* First create file */
$fd = fopen("$htmldir/guide$id.html", "w");

/* print HTML header */
fwrite($fd, "<HTML>\n<HEAD>\n<TITLE>$title</TITLE>\n");

fwrite($fd, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1255\">\n");

fwrite($fd, "<SCRIPT Language=JavaScript>\n");

fwrite($fd, "function Test(){\n", "w");
fwrite($fd, "  alert(\"Got here\");\n", "w");
fwrite($fd, "}\n\n", "w");

fwrite($fd, "function blocking(nr, t) {\n", "w");
fwrite($fd, "  if (document.getElementById){\n", "w");
fwrite($fd, "    current = (document.getElementById(nr).style.display == 'block') ? 'none' : 'block';\n", "w");
fwrite($fd, "    document.getElementById(nr).style.display = current;\n", "w");
fwrite($fd, "    document.getElementById(t).style.color='#ff0000';\n", "w");
fwrite($fd, "  }\n", "w");
fwrite($fd, "  else if (document.all) {\n", "w");
fwrite($fd, "    current = (document.all[nr].style.display == 'block') ? 'none' : 'block';\n", "w");
fwrite($fd, "    document.all[nr].style.display = current;\n", "w");
fwrite($fd, "    document.all[title].style.color='#ff0000';\n", "w");
fwrite($fd, "  }\n", "w");
fwrite($fd, "  else if (document.layers) {\n", "w");
fwrite($fd, "    var i = parseInt(nr.substr(nr.length-1,1));\n", "w");
fwrite($fd, "    var replacing = heights[i-1];\n", "w");
fwrite($fd, "    if (shown[i]) {\n", "w");
fwrite($fd, "      shown[i] = false;\n", "w");
fwrite($fd, "      replacing = -replacing;\n", "w");
fwrite($fd, "      document.layers[nr].visibility = 'hide';\n", "w");
fwrite($fd, "      document.layers[nr].top = safe;\n", "w");
fwrite($fd, "    }\n", "w");
fwrite($fd, "    else {\n", "w");
fwrite($fd, "      shown[i] = true;\n", "w");
fwrite($fd, "      document.layers[nr].visibility = 'show';\n", "w");
fwrite($fd, "      var tempname = 'header' + i;\n", "w");
fwrite($fd, "      document.layers[nr].top = document.layers[tempname].top + headerheight;\n", "w");
fwrite($fd, "    }\n", "w");
fwrite($fd, "    for (j=(i+1);j<=max;j++) {\n", "w");
fwrite($fd, "      name1 = 'header' + j;\n", "w");
fwrite($fd, "      document.layers[name1].top += replacing;\n", "w");
fwrite($fd, "      if (shown[j]) {\n", "w");
fwrite($fd, "	name2 = 'number' + j;\n", "w");
fwrite($fd, "	document.layers[name2].top += replacing;\n", "w");
fwrite($fd, "      }\n", "w");
fwrite($fd, "    }\n", "w");
fwrite($fd, "  }\n", "w");
fwrite($fd, "  else alert ('This link does not work in your browser.');\n", "w");
fwrite($fd, "}\n", "w");
fwrite($fd, "\n", "w");
fwrite($fd, "</SCRIPT>\n", "w");

fwrite($fd, "<STYLE>\n", "w");
fwrite($fd, ".para {display: none;}\n", "w");
fwrite($fd, "table { font-size: 14px; font-family: arial, sans-serif}\n", "w");
fwrite($fd, "body {margin:0; font-size: 12px, font-family: arial, sans-serif}\n", "w");
fwrite($fd, "a:visited {color:blue}\n", "w");
fwrite($fd, "a:link {color:navy; font-family:arial, sans-serif }\n", "w");
fwrite($fd, "a:hover {color:red}\n", "w");
fwrite($fd, ".text1 { font-size:10px; font-family: arial, sans-serif}\n", "w");
fwrite($fd, ".text2 { font-size:11px; font-family: arial, sans-serif}\n", "w");
fwrite($fd, ".text3 { font-size:14px; font-family: arial, sans-serif}\n", "w");
fwrite($fd, "h1 {font-size: 24; font-weight:bold; font-family: arial, sans-serif; color: navy}\n", "w");
fwrite($fd, "h2 {font-size: 18; font-weight:bold; font-family: arial, sans-serif; color: navy}\n", "w");
fwrite($fd, "</STYLE>\n", "w");

</HEAD>
<BODY >

