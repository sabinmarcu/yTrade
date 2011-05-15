<?php

class sales extends CI_Controller {
    
    const OPEN = 1;
    const SUCCESS = 2;
    const CANCEL = 3;
    
    
    public function g() {
        $sale = R::dispense('sale');
        $sale->userid = $this->session->userdata('userid');
        $sale->sell = 2;//$input['sell'];
        $sale->buy = 3;//$input['buy'];
        $sale->qty = 31;//$input['qty'];
        $sale->status = self::OPEN;
        R::store($sale);
    }

}
