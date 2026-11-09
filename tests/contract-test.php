<?php
require __DIR__.'/../wp-content/plugins/sexclick-operations/integration-contract.php';
$gateway=new \Sexclick\Integration\UnconfiguredGateway();
if(array_filter($gateway->capabilities()))throw new Exception('Unconfigured connector must not advertise live capabilities.');
foreach(['catalogue'=>[],'availability'=>[['SKU']],'shippingQuotes'=>[[],[]],'placeOrder'=>[[],'key'],'findOrder'=>['ref'],'tracking'=>['id']] as $method=>$args){
 try{$gateway->$method(...$args);throw new RuntimeException('Unconfigured method did not fail.');}catch(LogicException $e){}
}
echo "PASS: no unconfigured operation can transmit data.\n";
