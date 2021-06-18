<?php 
    require_once "config.php";
    session_start();

    $answer_id = $_POST['answer_id'];
    $vote = $_POST['vote'];
    $mes_type = "";
    $plus_votes = "";
    $minus_votes = "";

    if(!isset($_SESSION['std_id'])) {
        $mes_type = "L";
    } else {
        //Check vote already exists for a particular student
        $sql="select count(*) as total from votes where std_id='".$_SESSION['std_id']."' and answer_id = '".$answer_id."'";
        $result=mysqli_query($link,$sql);
        $data=mysqli_fetch_assoc($result);

        if($data['total'] == 0) {
            $sql = "INSERT INTO votes (std_id, answer_id, vote) VALUES ('".$_SESSION['std_id']."', '".$answer_id."', '".$vote."')";
            if(mysqli_query($link, $sql)){
                $sql1="select SUM(vote) as plus_votes from votes where answer_id = '".$answer_id."' and vote>0";
                $result1=mysqli_query($link,$sql1);
                $data1=mysqli_fetch_assoc($result1);
                $plus_votes = $data1['plus_votes'];
                if($plus_votes == "") {
                    $plus_votes = "0";
                }

                $sql2="select SUM(vote) as minus_votes from votes where answer_id = '".$answer_id."' and vote<0";
                $result2=mysqli_query($link,$sql2);
                $data2=mysqli_fetch_assoc($result2);
                $minus_votes = $data2['minus_votes'];
                if($minus_votes == "") {
                    $minus_votes = "0";
                }

                $sql3 = "UPDATE answers SET plus_votes='".$data1['plus_votes']."', minus_votes = '".$data2['minus_votes']."' WHERE id='".$answer_id."'";
                if(mysqli_query($link, $sql3)){
                    $mes_type = "S";
                } else {
                    $mes_type = "E";
                }
            } else {
                $mes_type = "E";
            }
        } else {
            $sql = "UPDATE votes SET vote='".$vote."' WHERE std_id='".$_SESSION['std_id']."' AND answer_id='".$answer_id."'";
            if(mysqli_query($link, $sql)){
                $sql1="select SUM(vote) as plus_votes from votes where answer_id = '".$answer_id."' and vote>0";
                $result1=mysqli_query($link,$sql1);
                $data1=mysqli_fetch_assoc($result1);
                $plus_votes = $data1['plus_votes'];
                if($plus_votes == "") {
                    $plus_votes = "0";
                }

                $sql2="select SUM(vote) as minus_votes from votes where answer_id = '".$answer_id."' and vote<0";
                $result2=mysqli_query($link,$sql2);
                $data2=mysqli_fetch_assoc($result2);
                $minus_votes = $data2['minus_votes'];
                if($minus_votes == "") {
                    $minus_votes = "0";
                }

                $sql3 = "UPDATE answers SET plus_votes='".$data1['plus_votes']."', minus_votes = '".$data2['minus_votes']."' WHERE id='".$answer_id."'";
                if(mysqli_query($link, $sql3)){
                    $mes_type = "S";
                } else {
                    $mes_type = "E";
                }
            } else {
                $mes_type = "E";
            }
        }        
    }

    echo json_encode(array("message_type"=>$mes_type, "plus_votes"=> $plus_votes, "minus_votes"=>$minus_votes));
?>