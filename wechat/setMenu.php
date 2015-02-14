<?php
/**
 * Created by IntelliJ IDEA.
 * User: vivaxy
 * Date: 150214
 * Time: 14:22
 */

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data) {

    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
        'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $postdata,
			'timeout' => 15 * 60 // 超时时间（单位:s）
		)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	return $result;
}

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

send_post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=rL8ZCIZPGl-oYN7UgsnpLPLTIWAdJTvgv0bachgPEiyrmJix9G0l8rqd8E_BdbVPl7IItSAWP4cyutcErao64jVgwi5PclOMkRhyUzAyolw', $post_data);