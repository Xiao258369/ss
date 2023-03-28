<?php
require_once("lib/epay.config.php");
require_once("lib/EpayCore.class.php");


// 开启 Session
session_start();

// 同步通知对象
$epay = new EpayCore($epay_config);

// 验证请求的签名是否正确
$verify_result = $epay->verifyReturn();
if ($verify_result) { // 验证成功
    $out_trade_no = $_GET['out_trade_no']; // 商户订单号
    $trade_no = $_GET['trade_no']; // 订单号
    $money = $_GET['money']; // 获取支付金额
    
if ($money == 1.00) {
        $package = '套餐一 流量100G一个月有效'; // 生成套餐一的账号信息
    } elseif ($money == 2.00) {
        $package = '套餐二 流量300G三个月有效'; // 生成套餐二的账号信息
    } elseif ($money == 3.00) {
        $package = '套餐三 流量1000G一年有效'; // 生成套餐三的账号信息
    } else {
        exit;
    }
    // 向后端服务器发送请求
    $data = array(
        'trade_no' => $trade_no,
        'money' => $money,
        'out_trade_no' => $out_trade_no,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://cnmrnm.cn:5533/api/create_account.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response, true);
    if ($response['status'] == 'success') {
        // 如果生成账号信息成功，将账号信息保存到 Session 中
        $_SESSION['account_info'] = $response['account_info'];
    }
}

// 判断 Session 中是否有账号信息，如果有则展示账号信息
if (!empty($_SESSION['account_info'])) {
    $account_info = $_SESSION['account_info'];
}
?>

<!DOCTYPE html>
<html lang="en">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<head><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>订阅成功</title>
    <link rel="stylesheet" href="css/jst.css">
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header text-center">订阅成功</div>
                    <div class="card-body">
                        <p>您的账号：<?php echo $account_info['account']; ?></p>
                        <p>您的密码：<?php echo $account_info['password']; ?></p>
                        <p>订阅链接：http://cnmrnm.cn:5533/api/v1/client/subscribe?token=<span><?php echo $account_info['subscribe_url']; ?></span></p>
                        <p>已为您开通<?php echo $package; ?></p>
                        <p style="color: red; font-weight: bold; font-size: 24px; text-align: center;">重要信息！</p>
                        <p style="color: red; font-weight: bold; text-align: center;">点击下方复制按钮将信息保存到你的记事本！或者截图这个界面！</p>
                        <button class="btn btn-primary" id="copy-btn" style="display: block; margin: 0 auto;">复制</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <p style="text-align: center;">确保您已复制保存您的账号信息后，点击下方按钮管理账号：</p>
                        <button class="btn btn-primary" onclick="window.open('../index.php', '_blank');">管理账号</button>
                        <button class="btn btn-primary" onclick="window.open('../index.php', '_blank');" style="float:right;">使用教程</button>
                        <button class="btn btn-primary" onclick="subscribe();" style="display:block; margin:0 auto; margin-top:20px;">一键订阅</button>
                      </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // 获取复制按钮和要复制的文本
        var copyBtn = document.getElementById('copy-btn');
        var text = "您的账号：" + "<?php echo $account_info['account']; ?>" + "\n" 
            + "您的密码：" + "<?php echo $account_info['password']; ?>" + "\n" 
            + "订阅链接：http://cnmrnm.cn:5533/api/v1/client/subscribe?token=<?php echo $account_info['subscribe_url']; ?>";

        // 当复制按钮被单击时，执行复制操作
        copyBtn.addEventListener('click', function() {
            // 创建临时textarea元素并将要复制的文本设置为其值
            var tempTextArea = document.createElement("textarea");
            tempTextArea.value = text;
            
            // 将临时textarea元素添加到文档中并选择其文本
            document.body.appendChild(tempTextArea);
            tempTextArea.select();

            // 执行复制命令并删除临时textarea元素
            document.execCommand("copy");
            document.body.removeChild(tempTextArea);

            alert("已复制到剪切板");
        });
         function subscribe() {
        var subscribeUrl = "http://cnmrnm.cn:5533/api/v1/client/subscribe?token=" + "<?php echo $account_info['subscribe_url']; ?>";
        var t = [];
        var userAgent = navigator.userAgent;

        if (/iPhone|iPad|iPod/i.test(userAgent)) {
            // iOS devices
            t.push({title: "Shadowrocket", href: "shadowrocket://add/sub://" + window.btoa(subscribeUrl + "&flag=shadowrocket").replace(/\+/g, "-").replace(/\//g, "_").replace(/=+$/, "") + "?remark=" + window.settings.title});
            t.push({title: "QuantumultX", href: "quantumult-x:///update-configuration?remote-resource=" + encodeURI(JSON.stringify({server_remote: [subscribeUrl + ", tag=" + window.settings.title]}))});
            t.push({title: "Surge", href: "surge:///install-config?url=" + encodeURIComponent(subscribeUrl) + "&name=" + window.settings.title});
t.push({title: "Stash", href: "stash://install-config?url=" + encodeURIComponent(subscribeUrl) + "&name=" + window.settings.title});
}
        if (/Android/i.test(userAgent)) {
            // Android devices
            t.push({title: "Clash For Android", href: "clash://install-config?url=" + encodeURIComponent(subscribeUrl) + "&name=" + window.settings.title});
            t.push({title: "Surfboard", href: "surge:///install-config?url=" + encodeURIComponent(subscribeUrl) + "&name=" + window.settings.title});
        }

        if (/Windows/i.test(userAgent)) {
            // Windows devices
            t.push({title: "Clash For Windows", href: "clash://install-config?url=" + encodeURIComponent(subscribeUrl) + "&name=" + window.settings.title});
        }

        // Trigger the first available app
        if (t.length > 0) {
            window.location.href = t[0].href;
        } else {
            alert('抱歉，我们无法识别您的设备类型。请尝试在另一台设备上使用此一键订阅功能。');
}
}
    </script>
</body>
</html>