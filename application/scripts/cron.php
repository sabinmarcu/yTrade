<?php

include('../third_party/rb/rb.php');

$BASE_URL = 'https://query.yahooapis.com/v1/public/yql';

$currencies = array(
    'EUR',
    'GBP',
    'USD',
    'CAD',
    'AUD',
    'XAU',
    'RON',
    'JPY',
    'CHF',
); // TODO: SELECT FROM DB

$pairs = array();
foreach ($currencies as $i) {
    foreach ($currencies as $j) {
        if ($i == $j) {
            continue;
        }
        $pairs[] = $i . $j;
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

$yql_query_url = $BASE_URL . '?' . $get;
$json = file_get_contents($yql_query_url);
$res = json_decode($json);

if (!is_null($res->query->results)) {
    foreach ($res->query->results->rate as $row) {
        echo $row->Name . ' is ' . $row->Rate;
        echo "<br/>\n";
    }
}
