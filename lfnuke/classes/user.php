<?
class user  {
    var $id;
    var $points="0";
    var $mode="ntba";
    
    /* The levels of premission:
       ntba - need to be authorized
       dead - can't access the site
       zero - Can only watch
       user - Can add, edit, and move his stuff
       move - can also move other ppl stuff
       edit - can also edit and remove other ppl stuff
       oper - can do anything */
    
    var $nickname;
    var $password;
    var $email;
    var $mess_net;/* istant messaging network */
    var $mess_id; /* istant messaging id */
    var $distro;  /* linux distribution */
    var $status="normal";
    var $first_time_password;    
    var $add_date;
    
    function user ($nickname, $password, $email, $mess_net, $mess_id, $distro, $add_date){
        $this->nickname = $nickname;
	$this->password = $password;
	$this->email = $email;
	$this->mess_net = $mess_net;
	$this->mess_id = $mess_id;
	$this->distro = $distro;
        $this->first_time_password = $first_time_password;
        $this->add_date = $add_date;    
	}
}
?>