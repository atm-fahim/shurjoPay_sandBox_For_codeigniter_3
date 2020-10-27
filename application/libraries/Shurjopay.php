<?php

class Shurjopay {

    private $merchant_username = 'spaytest';
    private $merchant_password = 'JehPNXF58rXs';
    private $client_ip = '206.189.133.213';
    private $merchant_key_prefix = 'NOK';
    private $server_url='https://shurjotest.com/';
            function __construct() {
        $this->CI = & get_instance();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

    }

    public function generateTxId($unique_id = null) {
        if ($unique_id) {
            $tx_id = $this->merchant_key_prefix . $unique_id;
        } else {
            $tx_id = $this->merchant_key_prefix . uniqid();
        }
        $this->tx_id = $tx_id;
        return $tx_id;
    }

    public function sendPayment($amount) {
        // echo $amount;exit;
        $success_url = null;
        //echo $this->merchant_password;
        //exit;
       $tx_id = $this->merchant_key_prefix . uniqid();
        //exit;
        $return_url = base_url('response');
//        if ($success_url) {
//            $return_url .= "?success_url={$success_url}";
//        }
        $xml_data = 'spdata=<?xml version="1.0" encoding="utf-8"?>
                            <shurjoPay><merchantName>' . $this->merchant_username . '</merchantName>
                            <merchantPass>' . $this->merchant_password . '</merchantPass>
                            <userIP>' . $this->client_ip . '</userIP>
                            <uniqID>' . $tx_id . '</uniqID>
                            <totalAmount>' . $amount . '</totalAmount>
                            <paymentOption>shurjopay</paymentOption>
                            <returnURL>' . $return_url . '</returnURL></shurjoPay>';
        //echo $xml_data;exit;

        $ch = curl_init();
        $server_url = $this->server_url;
        $url = $server_url . "/sp-data.php";
        //echo  $url;exit;
        //$url = "https://shurjotest.com/sp-data.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
        //exit;
    }

}
