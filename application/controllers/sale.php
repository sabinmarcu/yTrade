<?php

class Sale extends CI_Controller {
    const OPEN = 1;
    const SUCCESS = 2;
    const CANCEL = 3;

    public function index() {

        $q = "SELECT * FROM (SELECT * FROM sale WHERE userid != ? AND status = ? ) as  RIGHT JOIN user on user.id = sale.userid";
        $r = R::getAll($q, array($this->session->userid, self::OPEN));
    }

    public function create() {

        $inputs = array('sell', 'buy', 'qty');
        $input = array();

        foreach ($inputs as $in) {
            $input[$in] = $this->input->get($in);
        }

        if (in_array(NULL, $input)) {
            echo json_encode(array('error' => 'Invalid input data'));
            return;
        }

        $currency = R::findOne('currency', 'code = ?', array($input['sell']));
        if (!$currency) {
            echo json_encode(array('error' => 'Inexistent currency'));
            return;
        }
        $currency_id = $currency->id;

        $account = R::findOne('account', 'userid = ? and currency = ?', array(
                    $this->session->userdata('userid'),
                    $currency_id
                ));

        if (!$account || $account->qty < $input['qty']) {
            echo json_encode(array('error' => 'Insufficient funds'));
            return;
        }

        $account->amount = $account->amount - $input['qty'];
        R::store($account);

        $sale = R::dispense('sale');
        $sale->userid = $this->session->userdata('userid');
        $sale->sell = $input['sell'];
        $sale->buy = $input['buy'];
        $sale->qty = $input['qty'];
        $sale->status = self::OPEN;
        $sale->created_at = time();
        R::store($sale);
    }

    public function accept() {

        $id = $this->input->get('id');
        $sale = R::findOne('sale', 'id = ? and userid != ?',
                        array($id, $this->session->userdata('userid'))
        );

        if (!$sale) {
            echo json_encode(array('error' => 'Sale not found'));
            return;
        }

        $currency = R::findOne('currency', 'code = ?', array($sale->buy));
        if (!$currency) {
            echo json_encode(array('error' => 'Inexistent currency'));
            return;
        }
        $currency_id = $currency->id;

        $currency = R::findOne('currency', 'code = ?', array($sale->sell));
        if (!$currency) {
            echo json_encode(array('error' => 'Inexistent currency'));
            return;
        }
        $sell_currency_id = $currency->id;

        $account = R::findOne('account', 'userid = ? and currency = ?', array(
                    $this->session->userdata('userid'),
                    $currency_id
                ));

        $rate = R::findOne('rate', 'pair = ? ORDER BY timestamp DESC',
                        array($sale->sell . $sale->buy)
        );

        if (!$rate) {
            echo json_encode(array('error',
                'Exchange rates not available for this currencies')
            );
            return;
        }

        $ask = $rate->ask;
        $bid = $rate->bid;

        if ($ask == 0 || $bid == 0) {
            echo json_encode(array('error' => 'Insufficient funds'));
        }

        $to_pay = $ask * $sale->qty;
        $to_receive = $bid * $sale->qty;

        if (!$account || $account->qty < ($to_pay)) {
            echo json_encode(array('error' => 'Insufficient funds'));
            return;
        }

        $sale->status = self::SUCCESS;
        $sale->closed_at = time();
        $sale->accepted_by = $this->session->userdata('userid');
        $sale->accepted_ask = $ask;
        $sale->accepted_bid = $bid;
        R::store($sale);

        $account->amount = $account->amount - $to_pay;
        R::store($account);

        $seller_account = R::findOne('account', 'userid = ? and currency = ?',
                        array($sale->userid, $sell_currency_id)
        );

        if(!$seller_account){
            $seller_account = R::dispense('account');
            $seller_account->userid = $sale->userid;
            $seller_account->currency = $sell_currency_id;
            $seller_account->amount = $to_receive;
        }
        else{
            $seller_account->amount = $seller_account->amount + $to_receive;
        }

        R::store($seller_account);
    }

    public function cancel() {

        $id = $this->input->get('id');
        $sale = R::findOne('sale', 'id = ? and userid = ? and status = ?',
                        array($id, $this->session->userdata('userid'),)
        );

        if (!$sale) {
            echo json_encode(array('error' => 'Sale not found'));
            return;
        }

        $sale->status = self::CANCEL;
        $sale->closed_at = time();
        R::store($sale);
    }

}