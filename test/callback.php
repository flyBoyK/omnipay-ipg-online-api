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

$gateway = new \Omnipay\IpgOnline\Gateway();
$gateway->setStoreId('4700000023');
$gateway->setShareSecret('w84CLQh2tb');

$data = [
    'approval_code' => "Y",
    'oid' => "132575",
    'status' => 'approved',
    'ipg_transaction_id' => "abda-afaf-122",
    'store_id' => $gateway->getStoreId(),
    'share_secret' => $gateway->getShareSecret(),
    'charge_total' => '13.00',
    'currency' => '344',
    'datetime' => '1213',
    'response_hash' => '12131313'
];

/* @var \Omnipay\IpgOnline\Message\CompletePurchaseResponse $response */
$response = $gateway->completePurchase($data)->send();

var_dump($response->isSuccessful());
echo "<br>";

var_dump($response->getMessage());
echo '<br>';

var_dump($response->getTransactionId());
echo '<br>';

