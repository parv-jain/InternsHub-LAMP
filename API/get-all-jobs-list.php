<?php
header("Content-Type:application/json");
//connect

require_once 'db-config.php';

$res="false";

        $query = "select * from jobs";

        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) >= 1){
            $rows = array();
            while($row = mysqli_fetch_assoc($result)){
                $rows[]=$row;
            }
            $res = $rows;
        }
        else{
            $res="empty";
        }

print(json_encode($res));
?>
