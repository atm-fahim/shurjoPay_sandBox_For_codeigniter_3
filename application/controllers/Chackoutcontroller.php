<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chackoutcontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Shurjopay');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
//    public function index1() {
//        $data = array();
//        $this->load->view('frontend/Home_vot', $data);
//    }

    public function Checkout() {
        $data = array();
        $amount = $this->input->post('amount', true);
        $this->shurjopay->sendPayment($amount);
    }

    public function response() {
        $response_encrypted = $this->input->post('spdata', true);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://shurjopay.com/merchant/decrypt.php?data=" . $response_encrypted,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = array();
            $sp_data = simplexml_load_string($response) or die("Error: Cannot create object");
            $data['tx_id'] = $sp_data->txID;
            $data['bank_tx_id'] = $sp_data->bankTxID;
            $data['amount'] = $sp_data->txnAmount;
            $data['bank_status'] = $sp_data->bankTxStatus;
            $data['sp_code'] = $sp_data->spCode;
            $data['sp_code_des'] = $sp_data->spCodeDes;
            $data['sp_payment_option'] = $sp_data->paymentOption;
            echo json_encode($data);
        }
    }

}
