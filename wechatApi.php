<?php
/**
 * Author : vivaxy
 * Date   : 2014/8/12 11:16
 * Project: wechat-php
 * Package: 
 */
require "callback.php";
require "processMsg.php";

//define token
define("TOKEN", "Xy1234567890");
$wechatCallbackApi = new wechatCallbackApi();
$processMsg = new processMsg();
if (!isset($_GET["echostr"])){
    //reply message
    echo $processMsg->receiveMsg();
}else {
    //valid
    echo $wechatObj->valid();
}