<?
class fileup {
    var $tmp_file;
    var $file_name;
    var $max_size;
    var $type;
    var $file_type;
    var $file_size;
    var $with_pic;
    var $add_date;
    
    function fileup {
      $this->tmp_file = $tmp_file;
      $this->file_name = $file_name;
      $this->max_size = $max_size;
      $this->type = $type;
      $this->file_type = $file_type;
      $this->file_size = $file_size;
      $this->with_pic = $with_pic;
      $this->up_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y")); 
      uploadNcheck($tmp_file, $file_name, $max_size, $game_pic_type, $game_pic_file_type, $game_pic_file_size, "with_pic", $add_date);
      $this->add_date       = $add_date;
      }