<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255"
 http-equiv="content-type">
  <title>linuXfun games</title>
</head>
<body dir="rtl">
<br>
<div style="text-align: center;">הוספת משחק<br>
</div>
<br>
<form action="php/art.php" method="post" id="artwork_form"
 target="_self">
  <div style="text-align: right;"> </div>
  <table style="width: 100%; text-align: left;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right;">שם המשחק:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="game_name" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">תיאור:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><textarea
 name="game_desc" rows="3" cols="45"></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">תמונה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="game_pic" type="file"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">אתר בית:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="game_home" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">קטגוריה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="art_type">
        <option>נא לבחור את סוג המשחק</option>
	<option>אסטרטגיה</option>
	<option>ארקייד</option>
	<option>הרפתקה</option>
	<option>חשיבה</option>
	<option>יריות</option>
	<option>מסחרי</option>
	<option>נוסטלגים</option>
	<option>ספורט</option>
        <option>תלת מימד</option>
	</select>
        </td>
      </tr>
    </tbody>
  </table>
  <input value="הוספה" type="submit"> </form>
הערות:<br>
1. אי אפשר להעלות תמונות השוקלות יותר מ80k.<br>
2. נא לבדוק שהמשחק אינו קיים במאגר לפני שמוסיפים אותו.<br>
3. להוספת הורדות למשחק, נא ללכת אל "הורדה למשחק" ב"הוספה מהירה".<br>
</body>
</html>
