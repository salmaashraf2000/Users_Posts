<?php
require_once 'config.php';
trait mysql{
            
            private static $_config =array();
            private static $_link;
            private static $_result;
            
            
            private function __construct(array $config) 
            {
                if(count($config)!==4)
                {
                    throw new InvalidArgumentException('Invalid number of connection parameter');
                }
                
                self::$_config=$config;
                 
            }
            
            //connect to mysql  
            public static function connect()
            {
                global $config;
                self::$_config=$config;
                if(self::$_link===null)
                {
                    list($host,$user,$password,$database)= self::$_config;
                    if(!self::$_link=@mysqli_connect($host,$user,$password,$database))
                    {
                        throw new RuntimeException('error connecting to server'. mysqli_connect_error());
                    }
                    //remove values
                    unset($host,$user,$password,$database);
                }
                return self::$_link;
            }
            
            public function query($query)
            {
                if(!is_string($query) || empty($query))
                {
                    throw new InvalidArgumentException('query mot valid');
                }
                $this->connect();
                if(!self::$_result= mysqli_query(self::$_link,$query))
                {
                   
                    throw new RuntimeException('error executing query'.$query.mysqli_error(self::$_link));
                 
                }
                return self::$_result;
            }
            
            public function select($table,$where='',$fields='*',$order='')
            {
                $query='SELECT '.$fields.' FROM '.$table
                        .(($where)? ' WHERE '.$where :'')
                        .(($order)? ' ORDER BY '.$order:'');
                return $this->query($query);
            }
            
             public function selectJoin($table1,$where='',$fields='*',$order='',$table2,$left1=0,$on1='')
            {
                $query='SELECT '.$fields.' FROM '.$table1.(($left1)?' LEFT':'' ).' JOIN '.$table2.' ON '.$on1
                        .(($where)? ' WHERE '.$where :'')
                        .(($order)? ' ORDER BY '.$order:'');
                return $this->query($query);
            }
            
            public function insert($table, array $data)
            {
                $fields = implode(',', array_keys($data));
                $values = implode(',', array_map(array($this,Escape), array_values($data)));
                $query='INSERT INTO '.$table.' ('.$fields. ') '.' VALUES ('.$values.') ';
                $this->query($query);
                return ;
            }
            
         
            //escape special characters in string for use of SQL statements
            public function Escape($value)
            {
                $this->connect();
                if($value===null)
                {
                    $value='NULL';
                } else if(!is_numeric($value))
                {
                    $value="'".mysqli_real_escape_string(self::$_link, $value)."'";
                }
                return $value;
            }
            
      
            
            //close database connection
            public function disconnect() 
            {
                if(self::$_link===null)
                {
                    return;
                }
                 mysqli_close(self::$_link);
                self::$_link=null;
                return true;
            }
            
            //destructor to close database connection when instance of class destroyed
            public function __destruct() 
            {
                $this->disconnect();
            }
            
        }