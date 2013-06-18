<?php
header("Content-type:text/html; charset=utf-8");

include_once "PayU.cls.php";
$option  = array( 'merchant' => 'MERCHANT', 'secretkey' => 'SECRETKEY', 'debug' => 1 );


# Create form for request
$forSend = array (
					#'ORDER_REF' => $orderID, # Uniqe order 
					'ORDER_PNAME' => array( "Test_goods", "Тест товар №1", "Test_goods3" ), # Array with data of goods
					'ORDER_PCODE' => array( "testgoods1", "testgoods2", "testgoods3" ), # Array with codes of goods
					'ORDER_PINFO' => array( "", "", "" ), # Array with additional data of goods
					'ORDER_PRICE' => array( "0.10", "0.11", "0.12" ), # Array with prices of goods
					'ORDER_QTY' => array( 1, 2, 1 ), # Array with data of counts of each goods 
					'ORDER_VAT' => array( 0, 0, 0 ), # Array with VAT of each goods
					'ORDER_SHIPPING' => 0, # Shipping cost
					'PRICES_CURRENCY' => "UAH",  # Currency
					'LANGUAGE' => "RU",
					'BILL_FNAME' => "TEST" # ...  etc.
				  );

#Create form
if( !isset($_GET['answer']) && !isset( $_GET['ctrl'] ))
{
	$pay = PayU::getInst()->setOptions( $option )->setData( $forSend )->LU();
	echo $pay;
}

#Check for real BACK_REF
#$pay true|false
if (isset($_GET['ctrl']))
{
	
$pay = PayU::getInst()->setOptions( $option )->checkBackRef();
if ( $pay ) echo "Real request";
	else echo "Fake request";

}

# Read answer (IPN)
if( isset($_GET['answer']) )
{
	$payansewer = PayU::getInst()->setOptions( $option )->IPN();
	echo $payansewer;
}


# Complete after delivery (IDN)
if (isset($_GET['idn']))
{
$date = time();
		$forSend = array( 'ORDER_REF' => "12345678",  # Order of payment
						  'ORDER_AMOUNT' => "1.00",  # Amount of payment
						  'ORDER_CURRENCY' => "UAH",  # Currency
						  'IDN_DATE' => date("Y-m-d H:i:s", $date), # Date of payment
						  );
		$pay = PayU::getInst()->setOptions( $this->payuOpt )->setData( $forSend )->IDN();
}


if (isset($_GET['irn']))
{
$date = time();
		$forSend = array( 'ORDER_REF' => "12345678", # Order of payment
						  'ORDER_AMOUNT' => "1.00",  # Amount of payment (Если сумма меньше чем платеж, будет частичный возврат )
						  'ORDER_CURRENCY' => "UAH",  # Currency
						  'IRN_DATE' => date("Y-m-d H:i:s", $date), # Date of payment
						  );
		$pay = PayU::getInst()->setOptions( $this->payuOpt )->setData( $forSend )->IRN();
}		
?>