<?php
require_once 'mysql.php';
require_once 'config.php';
class Post{
    private $ID;
    private $Body;
    
    use mysql{
        mysql::__construct as private __mysqlconstruct;
    }
    
     public function __construct()
    {
        global $config;
        
       $this->__mysqlconstruct($config);
    }
    

    public function CheckPosts(){
        $dt = date("Y-m-d");
        $date= date( "Y-m-d", strtotime( "$dt -7 day" ) );
        $table1="Usersas u";
        $table2="Postsas p";
        $query="SELECT Name,Email from Users where ID NOT IN (SELECT u.ID FROM Users as u JOIN Posts as p ON u.ID=p.User_ID AND p.Date>'$date')";
        $result=$this->query($query);
        return mysqli_fetch_all($result,MYSQLI_ASSOC);
    }
    public function Mail(array $Names){
      
       foreach ($Names as $row){
           
        
         $to = $row['Email'];
         $subject = "This is subject";
         
        
         $message = "Hello ".$row['Name']." ,Long Time u didn't post at our App";
         
         $header = "From:salmaaashraf2000@gmail.com \r\n";
         $header .= "Cc:".$to."\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent...";
         }
       }   
    }
}

