<?php
require_once 'BaseController.php';

class FinanceController extends BaseController
{
    public function kursDollar()
    {
        $isSuperAdmin = $this->isSuperAdmin();
        if (!$isSuperAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $currentDate = date('Y-m-d');
        $currencyQuery = '
            SELECT 
                *
            FROM 
                currency_history
            WHERE 
                date = "'.$currentDate.'" and
                from_currency = "USD" and
                to_currency = "IDR"
            ;
        ';
        // $this->dd($currencyQuery);
        $currency = $this->db->raw($currencyQuery)->fetch_object();
        $usdToIdr = 0;
        if (!empty($currency)) {
            $usdToIdr = $currency->rate;
        }else{
            $url = "https://api.currencyfreaks.com/v2.0/rates/latest?apikey=".CURRENCYFREAKS_API_KEY;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
                exit;
            }
            curl_close($ch);
            $data = json_decode($response, true);
            
            $usdToIdr = $data['rates']['IDR'] ?? 'Tidak ditemukan';
            $data = [
                "date" => $currentDate,
                "from_currency" => 'USD',
                "to_currency" => 'IDR',
                "rate" => $usdToIdr,
                "created_by" => $this->user->id,
            ];
            
            $insertData = $this->db->insert("currency_history", $data);
        }

        // $this->dd($usdToIdr);
        $alert = $this->getMessage();
        $this->render('finance/kurs-dollar/index', [
            'alert' => $alert,
            'usdToIdr' => $usdToIdr,
        ]);
    }

    public function kursDollarRefresh()
    {
        $isSuperAdmin = $this->isSuperAdmin();
        if (!$isSuperAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $currentDate = date('Y-m-d');
        $currencyQuery = '
            SELECT 
                *
            FROM 
                currency_history
            WHERE 
                date = "'.$currentDate.'" and
                from_currency = "USD" and
                to_currency = "IDR"
            ;
        ';
        // $this->dd($currencyQuery);
        $currency = $this->db->raw($currencyQuery)->fetch_object();
        $usdToIdr = 0;
        if (isset($currency)) {
            $url = "https://api.currencyfreaks.com/v2.0/rates/latest?apikey=".CURRENCYFREAKS_API_KEY;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
                exit;
            }
            curl_close($ch);
            $data = json_decode($response, true);
            
            $usdToIdr = $data['rates']['IDR'] ?? 'Tidak ditemukan';
            $data = [
                "date" => $currentDate,
                "from_currency" => 'USD',
                "to_currency" => 'IDR',
                "rate" => $usdToIdr,
                "updated_by" => $this->user->id,
            ];
            $update = $this->db->update("currency_history", $data, 'id', $currency->id);
        }else{
            $url = "https://api.currencyfreaks.com/v2.0/rates/latest?apikey=".CURRENCYFREAKS_API_KEY;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
                exit;
            }
            curl_close($ch);
            $data = json_decode($response, true);
            
            $usdToIdr = $data['rates']['IDR'] ?? 'Tidak ditemukan';
            $data = [
                "date" => $currentDate,
                "from_currency" => 'USD',
                "to_currency" => 'IDR',
                "rate" => $usdToIdr,
                "created_by" => $this->user->id,
            ];
            
            $insertData = $this->db->insert("currency_history", $data);
        }
        $this->redirect('finance/kurs-dollar');
    }
}
