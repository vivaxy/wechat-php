<?php
/**
 * Author : vivaxy
 * Date   : 2014/8/12 11:16
 * Project: wechat-php
 * Package: 
 */


//define token
define("TOKEN", "Xy1234567890");
$wechatObj = new wechatCallbackapi();
$wechatObj->valid();

class wechatCallbackapi{
    public function valid(){
        $echoStr = $_GET["echostr"];

        //valid signature
        if($this->checkSignature()){
            echo $echoStr;
            //reply message
            $this->responseMsg();
            exit;
        }
    }

    public function responseMsg(){
        echo $this->receiveMsg();
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function receiveMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //消息模板
        $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
					</xml>";
        $imageTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                    </xml>";
        $voiceTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[voice]]></MsgType>
                        <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Voice>
                    </xml>";
        $videoTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[video]]></MsgType>
                        <Video>
                            <MediaId><![CDATA[%s]]></MediaId>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                        </Video>
                    </xml>";
        $musicTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        <Music>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <MusicUrl><![CDATA[%s]]></MusicUrl>
                            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                            <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        </Music>
                    </xml>";
        $newsTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>%s</Articles>
                    </xml>";
        $newsArticleTpl = "<item>
                                <Title><![CDATA[%s]]></Title>
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>";
        //extract post data
        if (!empty($postStr)){
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
            switch ($postMsgType){
                case "text":
                    //文本消息内容
                    $Content = $postObj->Content;
                    //回复
                    $contentStr = "you have sent"+$Content;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "image":
                    //图片链接
                    $PicUrl = $postObj->PicUrl;
                    //图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //回复
                    $contentStr = "PicUrl="+$PicUrl+"\nMediaId="+$MediaId;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "voice":
                    //语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //语音格式，如amr，speex等
                    $Format = $postObj->Format;
                    //回复
                    $contentStr = "MediaId="+$MediaId+"\nFormat="+$Format;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "video":
                    //视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $MediaId = $postObj->MediaId;
                    //视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    $ThumbMediaId = $postObj->ThumbMediaId;
                    //回复
                    $contentStr = "MediaId="+$MediaId+"\nThumbMediaId="+$ThumbMediaId;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
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
                    $contentStr = "Location_X="+$Location_X+"\nLocation_Y="+$Location_Y+"\nScale="+$Scale+"\nLabel="+$Label;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                case "link":
                    //消息标题
                    $Title = $postObj->Title;
                    //消息描述
                    $Description = $postObj->Description;
                    //消息链接
                    $Url = $postObj->Url;
                    //回复
                    $contentStr = "Title="+$Title+"\nDescription="+$Description+"\nUrl="+$Url;
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
                    break;
                default:
                    //回复
                    $contentStr = "unknown message type";
                    $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, $respTime, $contentStr);
            }
        }else{
            //回复
            $resultStr = "";
        }
        return $resultStr;
    }
}