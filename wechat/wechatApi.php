<?php
/**
 * Author : vivaxy
 * Date   : 2014/8/12 11:16
 * Project: wechat-php
 * Package:
 */
//define token
define("TOKEN", "Xy1234567890");
require "callback.php";
$wechatCallbackApi = new wechatCallbackApi();
require "processMsg.php";
$processMsg = new processMsg();
if (!isset($_GET["echostr"])) {
    //reply message
    $echo = $processMsg->receiveMsg();
    if ($echo != "") echo $echo;
} else {
    //valid
    echo $wechatCallbackApi->valid();
}