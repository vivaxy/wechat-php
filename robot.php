<?php
/**
 * Author : vivaxy
 * Date   : 2014/8/13 14:02
 * Project: wechat-php
 * Package: 
 */
class robot {
    public function answer($ask){
        require "mysql.php";
        $mysql = new mysql();
        $con = $mysql->getConnection();

        $result = mysql_query("select * from robot where ask = '".$ask."'", $con);

        $answerArray = array();

        while($row = mysql_fetch_array($result)){
            array_push($answerArray, $row["answer"]);
        }

        if (count($answerArray) == 0) return "还没人教过我怎么回答这个问题。";

        $rand = rand(0, count($answerArray)-1);

        $answer = $answerArray[$rand];
        //update usage
        mysql_query("update robot set lastUsed = NOW(), used = used + 1
            where ask = '".$ask."' and answer = '".$answer."'", $con);

        $mysql->closeConnection($con);

        return $answer;
    }
    public function teach($ask, $answer){
        require "mysql.php";
        $mysql = new mysql();
        $con = $mysql->getConnection();

        $result = mysql_query("select * from robot
            where ask = '".$ask."' and answer = '".$answer."'", $con);

        $resultLen = 0;

        while($row = mysql_fetch_array($result)){
            $resultLen = $resultLen + 1;
        }

        if ($resultLen == 0) {
            mysql_query("insert into robot(created, used, taught, ask, answer, isValid)
                VALUES(NOW(), 0, 1, '" . $ask . "', '" . $answer . "', 1)", $con);
        }else{
            mysql_query("update robot set taught = taught + 1
                where ask = '".$ask."' and answer = '".$answer."'", $con);
        }

        $mysql->closeConnection($con);
        return "教学成功! 现在问我 ".$ask." 试试看!";
    }
}
