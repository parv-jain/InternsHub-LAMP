<?php
header("Content-Type:application/json");
//connect

require_once 'db-config.php';

$res="false";

if(isset($_GET['job_id'])){
    if(!empty($_GET['job_id'])){

       $query = "SELECT u.full_name, a.student_id from users u, applications a where a.job_id = '".$_GET['job_id']."' AND a.student_id = u.user_id";

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
    }else{
        $res="false";
    }
}else{
    $res="false";
}


print(json_encode($res));
?>
