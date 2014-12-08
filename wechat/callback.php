<?php

/**
 * Author : vivaxy
 * Date   : 2014/8/13 13:00
 * Project: wechat-php
 * Package:
 */
class wechatCallbackApi
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature
        if ($this->checkSignature()) {
            return $echoStr;
        }
        return "unknow request";
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr == $signature;
    }
}