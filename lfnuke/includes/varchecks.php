<?
function isempty($var) /* Checks if var is empty */
    {
    if ($var == "") {return true;}
    else {return false;};
    }

function catempty($var)/* Checks if category is not selected properly */
    {
    if ($var == "נא לבחור את סוג המשחק") {return true;}
    else {return false;};
    };

function give_error($number)
    {
    header("location: ../php/index.php?error=".$number);
    exit;
    };

function valid_email ($email) {
            
   
   $regex  = "^[a-z0-9_%-]+@[a-z0-9._%-]+\.[a-z]{2,4}$";
   
   if (eregi($regex, $email)) return false;
   else return true;
   }

function valid_filename ($email) {
            
   $regex  = "^[a-z0-9-._]{1,25}.\..[a-z0-9-_]{1,25}$";
   
   if (eregi($regex, $email)) 
          {return false;} 
     else {return true; }
}
   
function valid_nick($name){
   // check valid input name
   if(eregi("^[a-z0-9_א-ת]{4,15}$",$name))
          {return false;} 
     else {return true; }
}

function valid_password($pwd){
   // check valid password
   if(eregi("^[a-z0-9]{6,12}$",$pwd))
   {
      return false;
   } else {
      return true;
   }
}

function valid_mess_id($mess_net,$mess_id) {
  if ($mess_net <> "ללא")
     {if ($mess_id == "") return true;
      if ($mess_net=="msn") 
               {if (valid_email($mess_id)) return true; else return false;}
      else {return false;};
     }
  else return false;
}

$this_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

?>