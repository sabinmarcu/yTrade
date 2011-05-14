#!/usr/bin/php
<?php
define('BASEPATH', '');
define('APPPATH', dirname(dirname(__FILE__)));
define('BASE_URL', 'https://query.yahooapis.com/v1/public/yql');

include('../libraries/rb.php');
new Rb(); // setup database

$currencies = R::find('currency');

$pairs = array();
foreach ($currencies as $i) {
    foreach ($currencies as $j) {
        if ($i->code == $j->code) {
            continue;
        }
        $pairs[] = $i->code . $j->code;
    }
}

$set = '"' . implode('","', $pairs) . '"';
$yql_query = 'SELECT * FROM yahoo.finance.xchange WHERE pair IN (' . $set . ')';

$params = array();
$params['q'] = $yql_query;
$params['format'] = 'json';
$params['env'] = 'store://datatables.org/alltableswithkeys';

$raw_params = array();
foreach ($params as $key => $value) {
    $raw_params[] = $key . '=' . urlencode($value);
}

$get = implode('&', $raw_params);

$yql_query_url = BASE_URL . '?' . $get;
$json = file_get_contents($yql_query_url);
$res = json_decode($json);

if (!is_null($res->query->results)) {
    foreach ($res->query->results->rate as $row) {
        $timestamp = strtotime($row->Date . ' ' . $row->Time);
        $prev = R::findOne('rate', 'timestamp=? and pair=?', array($timestamp, $row->id));
        if (!empty($prev)) {
            // no update since last query
            continue;
        }
        $rate = R::dispense('rate');
        $rate->pair = $row->id;
        $rate->timestamp = $timestamp;
        $rate->rate = $row->Rate;
        $rate->ask = $row->Ask;
        $rate->bid = $row->Bid;
        R::store($rate);
    }
}
