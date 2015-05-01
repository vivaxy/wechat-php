<?php
/**
 * Created by IntelliJ IDEA.
 * User: vivaxy
 * Date: 150214
 * Time: 14:22
 */
$token = 'qksTpjVqtE4D8j41HAntqr-xwS_4vu2SFmbHF6XveuGu2UieNrVJn65-s3p6dZHvCMfN1hrPddofz-lAjeamQg-UBYQJ51zNMUUl7yb46HA';

$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;

$post_data = '{
    "button": [
        {
            "name": "links",
            "sub_button": [
                {
                    "type": "view",
                    "name": "blog",
                    "url": "http://vivaxy.tk/"
                },
                {
                    "type": "view",
                    "name": "pages",
                    "url": "http://vivaxy.github.io/"
                }
            ]
        },
        {
            "name": "test",
            "sub_button": [
                {
                    "type": "click",
                    "name": "点击",
                    "key": "click_key"
                },
                {
                    "name": "发送位置",
                    "type": "location_select",
                    "key": "rselfmenu_2_0"
                }
            ]
        },
        {
            "name": "test",
            "sub_button": [
                {
                    "type": "scancode_waitmsg",
                    "name": "扫码带提示",
                    "key": "rselfmenu_0_0",
                    "sub_button": []
                },
                {
                    "type": "scancode_push",
                    "name": "扫码推事件",
                    "key": "rselfmenu_0_1",
                    "sub_button": []
                },
                {
                    "type": "pic_sysphoto",
                    "name": "系统拍照发图",
                    "key": "rselfmenu_1_0",
                    "sub_button": []
                },
                {
                    "type": "pic_photo_or_album",
                    "name": "拍照或者相册发图",
                    "key": "rselfmenu_1_1",
                    "sub_button": []
                },
                {
                    "type": "pic_weixin",
                    "name": "微信相册发图",
                    "key": "rselfmenu_1_2",
                    "sub_button": []
                }
            ]
        }
    ]
}';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

echo curl_exec($ch);