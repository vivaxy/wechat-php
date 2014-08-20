Oops!
<?php

/**
 * Author : vivaxy
 * Date   : 2014/8/19 10:35
 * Project: wechat-php
 * Package:
 */
class mysql
{
    public function getConnection()
    {
        $con = mysql_connect("localhost", "loveyqqt", "123456");
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("loveyqqt_narvixy", $con);
        mysql_query("set names 'utf-8'", $con);
        return $con;
    }

    public function closeConnection($con)
    {
        mysql_close($con);
    }
}