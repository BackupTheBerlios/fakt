<?PHP
/*
 | Configuration parameters for FAKT blogs system
 |
 | FAKT Free Authoring Knowledge & Thinking
 | Copyright: Helicon technologies LTD. 2003
 | The FAKT system is distributed under the GNU public license
 |
 | This file is part of FAKT forums system.
 |
 | FAKT system is free software; you can redistribute it and/or modify
 | it under the terms of the GNU General Public License as published by
 | the Free Software Foundation;
 |
 | FAKT system is distributed in the hope that it will be useful,
 | but WITHOUT ANY WARRANTY; without even the implied warranty of
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 | GNU General Public License for more details.
 |
 | You should have received a copy of the GNU General Public License
 | along with the software; if not, write to the Free Software
 | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 |
 */
/*
 | MySQL host settings
 */
$host='localhost';
/* If you live $user and $pswd empty, then MySQL user will be the current web server user
    default to 'nobody' in apache web server */
$user='fakt';
$pswd='forums';
$database='fakt';

/*
 | scripts addresses
 */
/* base address for all scripts */
$scriptsurl='http://fakt.tmt.org.il';
$home = $_SERVER['SERVER_ROOT'];

/*
 | place for emoticons images
 */
$emoticons="$home/emoticons";  /* file system location of emoticons direcotry */
$emoticonsurl="$scriptsurl/emoticons";	   /* url of emoticons directory, must correspond to $emoticons */

/* $userfiles is not used in this version, will be used in future versions */
$userfiles = "$scriptsurl/userfiles";
$userfilesdir="$home/userfiles";

/*
 | Mail inform
 */
$subject = 'New message in blog';
$adminmail = 'webmaster@fakt.tmt.org.il';
$replymail = 'noreply@fakt.tmt.org.il';

/*
 | Page appearence parameters
 */
/* number of messages in page */
$MsgPerPage=20;
/* $bodyparam is added inside the <BODY> directive as <BODY $bodyparam> use for background color
    etc */
$bodyparam='';
/* title for forums page */
$title='מערכת הבלוגים של FAKT';
/* base URL may be used to load images from a different base directory */
//$base='http://esc.sail.co.il/~ori';

/* Header of page, will appear below the <BODY ...> statement */
$pageheader = <<<EOD
<TABLE bgcolor=#5272A4 width=100% cellpadding=0 cellspacing=0><TR>
<TD><A HREF=fakt.html target=_blank><IMG SRC=fakt.jpg></A>
<TD><BR>

<TD dir=RTL valign=center><FONT color=white>
<A HREF=$scriptsurl/blogs.php><IMG src=doc_open.gif border=0><FONT color=white><BIG>
רשימת הבלוגים
</A>
</TABLE>
</CENTER>
EOD;
?>
