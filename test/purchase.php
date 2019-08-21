<?php

require_once '../vendor/autoload.php';

function errorLog($errNo, $errStr, $errFile, $errLine)
{
    switch ($errNo) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    file_put_contents('error.log', 'PHP ' . $error . ':  ' . $errStr . ' in ' . $errFile . ' on line ' . $errLine . "\n");
}
set_error_handler('errorLog');



$gateway = new \Omnipay\IpgOnlineApi\Gateway();
$gateway->setRequestUrl('https://test.ipg-online.com/connect/gateway/processing');
$gateway->setRequestTestUrl('https://test.ipg-online.com/connect/gateway/processing');
$gateway->setPurchaseRequestCallbackUrl('http://petcircle.com/api/pay/ipg_online/callback');

$gateway->setStoreId('4700000023');
$gateway->setUserId('4700000023');
$gateway->setUserPassword('asdf@1234');
$gateway->setMerchantName('King State Technology Limited');
$gateway->setShareSecret('w84CLQh2tb');

// 设置支付环境
$gateway->setEnvironment('development');

$request = $gateway->purchase([
    'store_id' => $gateway->getStoreId(),
    'user_id' => $gateway->getUserId(),
    'user_password' => $gateway->getUserPassword(),
    'share_secret' => $gateway->getShareSecret(),
    'merchant_name' => $gateway->getMerchantName(),

    'request_url' => $gateway->getRequestUrl(),
    'request_test_url' => $gateway->getRequestTestUrl(),
    'callback_url' => 'https://pet1718.com/api/pay/ipg_online/callback',

    'pay_info' => [
        'pay_id' => '121898',
        'request_type' => 'app_ios',
        'amount' => '13.00',
    ],
    'environment' => $gateway->getEnvironment(),

    'language' => 'zh_TW',
    'currency' => '344',
    'timezone_name' => 'Asia/Shanghai',
]);



/* @var \Omnipay\Common\Message\RedirectResponseInterface $response */
$response = $request->send();

if ($response->isRedirect()) {
    $redirectMethod = $response->getRedirectMethod();

    // 直接转向
    if ($redirectMethod == 'GET') {
        $redirectUrl = $response->getRedirectUrl();
        $this->response->redirect($redirectUrl);

    // 显示表单
    } elseif ($redirectMethod == 'POST' && method_exists($response, 'getRedirectHtml')) {
        $redirectHtml = $response->getRedirectHtml();
        echo $redirectHtml;
    }
    exit;
}