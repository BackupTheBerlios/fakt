<?PHP
/*
 | Automatcily transfer all requests to fakt.hosites.com/index.php to fakt.tmt.org.il
 | Ori Idan 2004
 */
?>
<HTML>
<HEAD>
<?
$forum = $_GET['forum'];
if(empty($forum))
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=http://fakt.tmt.org.il/index.php\">\n";
else
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=http://fakt.tmt.org.il/index.php?forum=$forum\">\n";
?>
</HEAD>
<BODY>
<CENTER>
<H1>This address is not active anymore</H1>
The new address is: <A HREF=http://fakt.tmt.org.il/index.php>http://fakt.tmt.org.il/index.php</A>
in few seconds you will be trasfered to the new address.<BR>

</BODY>
</HTML>
	
