<?
calss news_link {
   var $news_id;
   var $name;
   var $subject;
   var $the_link;
   
   function news_link($news_id,$name, $subject, $thelink) {
   $this->name = $name;
   $this->subject = $subject;
   $this->the_link = $the_link; } 
   }
   
class news {
   var $id;
   var $head;
   var $by;
   var $date;
   var $time;
   var $logo_filename;
   var $the_news;
   var $add_date;
   function news($head, $by, $logo_filename, $the_news, $add_date) {
   $this->head = $head;
   $this->by = $by;
   $this->logo_filename = $logo_filename;
   $this->the_news = $the_news; }
   $this->add_date       = $add_date;
   }