<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="he">
<head>
  <meta content="text/html; charset=CP1255"
 http-equiv="content-type">
  <title></title>
</head>
<? include "../includes/page_head.php"; ?>
<body
 style="color: rgb(0, 0, 0); background-color: rgb(255, 255, 255); direction: rtl;"
 link="#000099" alink="#000099" vlink="#990099">
      
<table style="width: 100%; text-align: left;" border="0" cellpadding="0"
 cellspacing="0">
  <tbody>
    <tr>
      <td style="width: 10px;" align="undefined" valign="undefined"><img
 alt="" src="images/classic/l-ur.gif" style="width: 10px; height: 10px;"></td>
      <td align="undefined" valign="undefined"><img alt=""
 src="images/classic/horline.gif" style="width: 100%; height: 10px;"></td>
      <td style="width: 10px;" align="undefined" valign="undefined"><img
 alt="" src="images/classic/l-ul.gif" style="width: 10px; height: 10px;"></td>
    </tr>
    <tr>
      <td style="width: 10px;" align="undefined" valign="undefined"><img
 alt="" src="images/classic/verline.gif"
 style="width: 10px; height: 100%;"></td>
      <td style="text-align: center;" valign="undefined">
      
      
      <?      
$action=$_GET['action'];
 if ($action=="add_app"    ) { include "../forms/app_add.php"; };
 if ($action=="add_app_dwn") { include "../forms/app_add_dwn.php"; };
 if ($action=="add_game")    { include "../forms/game_add.php"; };
 if ($action=="add_game_dwn"){ include "../forms/game_add_dwn.php"; };
 if ($action=="add_file")    { include "../forms/file_add.php" ;};
 if ($action=="user")        { include "../forms/user.php"; };
 if ($action=="userset")     { include "../includes/user.php"; };
 if ( $error<>"")            { include "../includes/error_msg.php"; echo $error_msg[$error];};
 if ($action="install"){ include "../includes/install.php"; install("now"); };
?>
      
  
      </td>
      <td style="width: 10px;"><img alt=""
 src="images/classic/verline.gif" style="width: 10px; height: 100%;"></td>
    </tr>
    <tr>
      <td style="width: 10px;" align="undefined" valign="undefined"><img
 alt="" src="images/classic/l-dr.gif" style="width: 10px; height: 10px;"></td>
      <td align="undefined" valign="undefined"><img alt=""
 src="images/classic/horline.gif" style="width: 100%; height: 10px;"></td>
      <td style="width: 10px;" align="undefined" valign="undefined"><img
 alt="" src="images/classic/l-dl.gif" style="width: 10px; height: 10px;"></td>
    </tr>
  </tbody>
</table>
  


</td>
</table>
<? include "../includes/page_down.php"; ?>
</body>
</html>
