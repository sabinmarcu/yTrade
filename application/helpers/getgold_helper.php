<?php

function getgold ($userid) {
   $moneys = R::find('account', 'userid=?', array($userid));
   if (!$moneys) return 0;
   $xau = 0;
   foreach ($moneys as $cur) {
       if ($cur->currency == 6) {
           $xau += $cur->amount;
       } else { 

           $moneda = R::findOne('currency','id=?',array($cur->currency));
//                      var_dump($moneda);
           if (!$moneda) return false;
//           var_dump($moneda);
           $xau+=exchange(
                   ($moneda->code . 'XAU'),
                   $cur->amount);
       }
   }
   return $xau;   
}

function exchange ($pair,$amount) {
    //getting the last exchange rate
   // var_dump ($pair);
    $rate = R::findOne('rate','pair=?',array($pair));
    if (!$rate) return false;
    return $amount * $rate->rate;    
}