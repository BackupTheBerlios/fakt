<?
class one_file {
    var $temp;          /* Temporary file          */
    var $name;          /* Name                    */
    var $size;          /* Size                    */
    var $type;          /* Type                    */
    var $category;      /* File category           */
    var $filename;      /* uploaded to             */
    var $add_date;
    
    function one_file($temp, $name, $size, $type, $category, $filename, $add_date) {
    $this->temp           = $temp;
    $this->name           = $desc;
    $this->size           = $size;
    $this->type           = $type;
    $this->category       = $category;
    $this->add_date       = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
    $this->filename       = $filename;
    $this->add_date       = $add_date;
    }
    }
?>
