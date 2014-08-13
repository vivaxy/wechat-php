<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/**
 * Author : vivaxy
 * Date   : 2014/8/13 14:02
 * Project: wechat-php
 * Package: 
 */
class mysql {
    public function answer($ask){
        $con = mysql_connect("localhost","loveyqqt","123456");
        if (!$con){
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("loveyqqt_narvixy", $con);
        mysql_query("set names 'utf-8'");

        $result = mysql_query("SELECT * FROM robot WHERE ask = '帮助'");

        $answerArray = array();

        while($row = mysql_fetch_array($result)){
            array_push($answerArray, $row["answer"]);
        }

        if (count($answerArray) == 0) return "还没人教过我怎么回答这个问题。";

        $rand = rand(0, count($answerArray)-1);

        mysql_close($con);

        return $answerArray[$rand];
    }
    public function teach(){

    }
}
