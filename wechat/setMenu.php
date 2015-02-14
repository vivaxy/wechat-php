<?php
/**
 * Created by IntelliJ IDEA.
 * User: vivaxy
 * Date: 150214
 * Time: 14:22
 */
$token = 'o0OSxm_xqjOmZiZ5y0xSfKiu6fcIdXA3yxr8J7ky8OiLKEfKzRw5s8T0oaAYLd8YNgWy2ZgqqVVfdDlnzqzlPMe6Q2akGkuIwLrFBo2Vza8';

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