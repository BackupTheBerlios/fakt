<?
class game {
    var $name;          /* Game name               */
    var $desc;          /* Description             */
    var $pic_loc;       /* Picture location        */
    var $home;          /* Game Home site          */
    var $category;      /* Game category           */
    var $add_date;      /* When the game was added */
    /* Game grades */	         
    var $game_grade;    /* Game grade                */
    var $graphics_grade;/* Graphics grade            */
    var $music_grade;   /* Music grade               */   
    var $story_grade;   /* Game Story grade          */
    var $game_hardness; /* How much hard is the game */
    var $add_date;
    
    /* End of Grades section */
    function game($name, $desc, $pic_loc, $home, $category, $game_grade, $graphics_grade,
                  $music_grade, $story_grade, $game_hardness, $add_date;) {
    $this->name           = $name;
    $this->desc           = $desc;
    $this->pic_loc        = $pic_loc;
    $this->home           = $home;
    $this->category       = $category;
    $this->add_date       = $add_date;
    $this->graphics_grade = $graphics_grade;
    $this->music_grade    = $music_grade;
    $this->story_grade    = $story_grade;
    $this->game_hardness  = $game_hardness;
    $this->game_grade     = round(($graphics_grade+$music_grade+$story_grade+$game_hardness)/4);
    $this->add_date       = $add_date;
    }
    }
?>