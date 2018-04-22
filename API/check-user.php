<?php
//connect
require_once 'db-config.php';

//Add user check parameters of get request
$res="false";
//check if keys exist
if(isset($_GET['username']) && isset($_GET['password'])){
    //check if values of key are not empty
    if(!empty($_GET['username']) && !empty($_GET['password'])){

        $query = "select user_type from users Where username = '".$_GET['username']."' AND password = '".$_GET['password']."'";

        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $res = $row['user_type'];
        }
        else{
            $res="false";
        }
    }
    else{
        $res="false";
    }
}
else{
    $res = "false";
}
echo $res;
?>
