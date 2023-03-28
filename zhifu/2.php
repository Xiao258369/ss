<?php
$out_trade_no = date("YmdHis").mt_rand(100,999);
$yueka = '季卡';
$qian = '2';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>收银台</title>
    <link rel="stylesheet" href="css/syt.css">
    <style>

    </style>
</head><meta name="viewport" content="width=device-width, initial-scale=1">
<body>
    <div class="con">
        <span class="title">收银台</span>
        </div>
        <div class="container">
        <form name="alipayment" action="epayapi.php" method="post" target="_self">
            <div class="form-row">
                <label for="out_trade_no">订单号：</label>
                <?php echo $out_trade_no; ?>
                <input type="hidden" name="WIDout_trade_no" value="<?php echo $out_trade_no; ?>">
            </div>
            <div class="form-row">
                <label for="yueka">套餐：</label>
                <?php echo $yueka; ?>
                <input type="hidden" name="WIDsubject" value="<?php echo $yueka; ?>">
            </div>
            <div class="form-row">
                <label for="qian">付款金额：</label>
                <span class="red-text"><?php echo $qian; ?>元</span>
                <input type="hidden" name="WIDtotal_fee" value="<?php echo $qian; ?>">
            </div>
            <div class="form-row">
                <label>支付方式：</label>
                <div class="payment-options">
                    <div class="payment-method">
                        <input type="radio" name="type" value="alipay" id="alipay">
                        <label for="alipay">
                            <img src="tu/alipay.ico" alt="支付宝">
                            <span>支付宝</span>
                        </label>
                    </div>
                    
                </div>
            </div>
            <div class="form-row">
                <label></label>
                <input type="submit" value="确认">
            </div>
        </form>
        <div id="foot">
            
        </div>
    </div>
</body>
</html>