<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255"
 http-equiv="content-type">
  <title>Add download linuxfun</title>
</head>
<body dir="rtl">
<br>
<div style="text-align: center;">����� ����� �����<br>
</div>
<form action="../add/dwn_game.php" method="post" id="dwn_app"
 target="_self"><br>
  <table style="width: 100%; text-align: left;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right;">�� �����:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="dwn_type">
        <option>�� ����</option>
<?
// ��� ���� ��� ������ �� ����� ������� ������
?>        
        
        </select>
&nbsp; </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">��� ������:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="dwn_type">
        <option>�� ����� �� ��� ������</option>
        <option> ������ </option>
        <option> ��� ���� </option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">����:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_game_distro" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">�����:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><textarea
 name="dwn_game_desc" rows="3" cols="45"></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">���� ������:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_game_location" size="45" maxlenght="45" value="http://"
 style="direction: ltr;"></td>
      </tr>
    </tbody>
  </table>
  <input value="��� �����" type="submit"> </form>
�����:<br>
1. �� ����� ������� ���� ����� ��� ���� ��������.<br>
</body>
</html>