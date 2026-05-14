<?php
class sqldb{
    public function getConnection()
    {
            $serverName = "172.25.37.11";
           
            $connectionInfo = array( "Database" => "dbUtilizedSpaces_Copy", "UID" => "dbUtilizedSpaces_User", "PWD" => "##789123$$" );
            //$connectionInfo = array( "Database" => "HBReservation", "UID" => "sa", "PWD" => "sa123" );
            $conn = sqlsrv_connect( $serverName, $connectionInfo);

            if( $conn === false) {
                 echo "Connection Error.<br />";
                 die(print_r(sqlsrv_errors(), true));

            }
            else
            {
                
                return $conn;
            }
        
    }   
}







?>