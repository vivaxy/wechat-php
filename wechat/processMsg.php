<?php

/**
 * Author : vivaxy
 * Date   : 2014/8/13 12:57
 * Project: wechat-php
 * Package:
 */
class processMsg
{
    public function receiveMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        require "templates.php";
        $tpl = new templates();
        //extract post data
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postMsgType = $postObj->MsgType;
            //开发者微信号
            $FromUserName = $postObj->FromUserName;
            //发送方帐号（一个OpenID）
            $ToUserName = $postObj->ToUserName;
            //消息创建时间 （整型）
            $CreateTime = $postObj->CreateTime;
            //消息id，64位整型
            $MsgId = $postObj->MsgId;
            //根据消息类型获取用户发送的消息
            $respTime = time();
            switch ($postMsgType) {
                case "text":
                    //文本消息内容
                    $Content = $postObj->Content;
                    require "robot.php";
                    $robot = new robot();
                    if (preg_match("/问 .+ 答 .+/", $Content)) {
                        //教学
                        $str = preg_split("/问 | 答 /", $Content);
                        $ask = $str[1];
                        $answer = $str[2];
                        $contentStr = $robot->teach($ask, $answer);
                    } elseif (preg_match("/vivaxy select .+/", $Content)) {
                        // 查看 模式
                        // mysql connection
                        require "mysql.php";
                        $mysql = new mysql();
                        $con = $mysql->getConnection();
                        // get column names
                        $selectString = preg_split("/vivaxy select | where | limit /", $Content, 0, PREG_SPLIT_NO_EMPTY)[0];

                        $columnArray = preg_split("/,/", $selectString, 0, PREG_SPLIT_NO_EMPTY);
                        // get condition clauses
                        // where
                        $whereClauses = "";
                        if (preg_match("/ where /", $Content)) {
                            $whereClauses = preg_split("/ where /", $Content, 0, PREG_SPLIT_NO_EMPTY)[1];
                            $whereClauses = preg_split("/ limit /", $whereClauses, 0, PREG_SPLIT_NO_EMPTY)[0];
                            $whereClauses = " where " . $whereClauses;
                        }
                        // limit
                        $limitClauses = "";
                        if (preg_match("/ limit /", $Content)) {
                            $limitClauses = preg_split("/ limit /", $Content, 0, PREG_SPLIT_NO_EMPTY)[1];
                            $limitClauses = preg_split("/ where /", $limitClauses, 0, PREG_SPLIT_NO_EMPTY)[0];
                            $limitClauses = " limit " . $limitClauses;
                        }
                        // get mysql query string
                        $queryString = "select " . implode(",", $columnArray) . " from robot" . $whereClauses . $limitClauses;
                        // get mysql result
                        $result = mysql_query($queryString, $con);
                        // get echo string
                        $contentStr = $queryString . "\n" . implode(" | ", $columnArray) . "\n";
                        while ($row = mysql_fetch_row($result)) {
                            $contentStr = $contentStr . implode(" | ", $row) . "\n";
                        }
                        // close mysql connection
                        $mysql->closeConnection($con);
                    } elseif (preg_match("/vivaxy delete .+/", $Content)) {
                        // 删除 模式
                        // mysql connection
                        require "mysql.php";
                        $mysql = new mysql();
                        $con = $mysql->getConnection();
                        // get column names
                        $deleteId = preg_split("/vivaxy delete /", $Content, 0, PREG_SPLIT_NO_EMPTY)[0];

                        // get mysql query string
                        $queryString = "delete from robot where id = " . $deleteId;
                        // get mysql result
                        $result = mysql_query($queryString, $con);
                        // get echo string
                        $contentStr = $queryString . "\n" . $result;
                        // close mysql connection
                        $mysql->closeConnection($con);
                    } else {
                        //回复
                        $contentStr = $robot->answer($Content);
                        if ($contentStr == "") {
                            //$contentStr = $Content;
                            $contentStr = "嗯~";
                        }
                    }
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "image":
                    //图片链接
                    $PicUrl = $postObj->PicUrl;
                    //图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //回复
                    $contentStr = "PicUrl=" . $PicUrl . "\nMediaId=" . $MediaId;
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "voice":
                    //语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //语音格式，如amr，speex等
                    $Format = $postObj->Format;
                    //回复
                    $contentStr = "MediaId=" . $MediaId . "\nFormat=" . $Format;
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "video":
                    //视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    $ThumbMediaId = $postObj->ThumbMediaId;
                    //回复
                    $contentStr = "MediaId=" . $MediaId . "\nThumbMediaId=" . $ThumbMediaId;
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "location":
                    //地理位置维度
                    $Location_X = $postObj->Location_X;
                    //地理位置经度
                    $Location_Y = $postObj->Location_Y;
                    //地图缩放大小
                    $Scale = $postObj->Scale;
                    //地理位置信息
                    $Label = $postObj->Label;
                    //回复
                    $contentStr = "Location_X=" . $Location_X . "\nLocation_Y=" . $Location_Y . "\nScale=" . $Scale . "\nLabel=" . $Label;
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "link":
                    //消息标题
                    $Title = $postObj->Title;
                    //消息描述
                    $Description = $postObj->Description;
                    //消息链接
                    $Url = $postObj->Url;
                    //回复
                    $contentStr = "Title=" . $Title . "\nDescription=" . $Description . "\nUrl=" . $Url;
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "event":
                    $Event = $postObj->Event;
                    switch ($Event) {
                        case "subscribe":
                            //事件KEY值，qrscene_为前缀，后面为二维码的参数值
                            $EventKey = $postObj->EventKey;
                            //二维码的ticket，可用来换取二维码图片
                            $Ticket = $postObj->Ticket;
                            if ($EventKey == "" && $Ticket == "") {
                                $contentStr = "来来来！输入\"帮助\"查看帮助。";
                            } else {
                                $contentStr = "EventKey=" . $EventKey . "\nTicket=" . $Ticket;
                            }
                            break;
                        case "unsubscribe":
                            $contentStr = "取消关注";
                            break;
                        case "SCAN":
                            //事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
                            $EventKey = $postObj->EventKey;
                            //二维码的ticket，可用来换取二维码图片
                            $Ticket = $postObj->Ticket;
                            $contentStr = "EventKey=" . $EventKey . "\nTicket=" . $Ticket;
                            break;
                        case "LOCATION":
                            //地理位置纬度
                            $Latitude = $postObj->Latitude;
                            //地理位置经度
                            $Longitude = $postObj->Longitude;
                            //地理位置精度
                            $Precision = $postObj->Precision;
                            $contentStr = "Latitude=" . $Latitude . "\nLongitude=" . $Longitude . "\nPrecision=" . $Precision;
                            break;
                        case "CLICK":
                            //事件KEY值，与自定义菜单接口中KEY值对应
                            $EventKey = $postObj->EventKey;
                            $contentStr = "EventKey=" . $EventKey;
                            break;
                        case "VIEW":
                            //事件KEY值，与自定义菜单接口中KEY值对应
                            $EventKey = $postObj->EventKey;
                            $contentStr = "EventKey=" . $EventKey;
                            break;
                        default:
                            $contentStr = "unknown message type";
                            break;
                    }
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                default:
                    //回复
                    $contentStr = "unknown message type";
                    $resultStr = sprintf($tpl::textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
            }
        } else { //empty post string
            //return empty string
            $resultStr = "";
        }
        return $resultStr;
    }
}