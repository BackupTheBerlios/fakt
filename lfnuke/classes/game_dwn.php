<?
class game_download {
    var $name;          /* Game name               */
    var $desc;          /* Description             */
    var $loc;           /* Download location       */
    var $type;          /* Download type           */
    var $comments;      /* Download comments       */
    var $distro;      /* Download comments       */
    var $add_date;
    
    /* End of Grades section */
    function game($name, $desc, $pic_loc, $home, $category, $game_grade, $graphics_grade,
                  $music_grade, $story_grade, $game_hardness, $add_date) {
    $this->name           = $name;
    $this->add_date       = $add_date;

    }
    }
?>