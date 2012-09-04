(For PayU Ukraine and Russian ONLY)
-------

Стандартный класс для интеграции PayU 
========

1. подключение файла с классом PayU
--------

    include_once "PayU.cls.php";
    $option  = array( 'merchant' => 'MERCHANT', 
                      'secretkey' => 'SECRETKEY' 
                      /*[, 'debug' => 1 ...] изменение доп параметров */ 
                    );

>Дополнительные параметры : 
>
>luUrl : Ссылка для LiveUpdate, по-умолчанию : https://secure.payu.ua/order/lu.php
>
>button : Контент кнопки (также можно добавить дополнительные поля или контент) , по-умолчанию : `<input type='submit'>`
>
>debug : Включить режим отладки, по-умолчанию выключен : 0, для включения передать 1
>
>showinputs : Задает тип для input (использовать для проверки полей), по-умолчанию  "hidden"


2. Генерация формы для создания запроса на PayU
--------

Создаем массив данных : 

    $forSend = array (
          #'ORDER_REF' => $orderID, # Ордер. Если не указывать - создастся автоматически
          #'ORDER_DATE' => date("Y-m-d H:i:s"), # Дата платежа ( Y-m-d H:i:s ). Необязательный параметр.
          'ORDER_PNAME' => array( "Test_goods", "Тест товар №1", "Test_goods3" ), # Массив с названиями товаров
          'ORDER_PCODE' => array( "testgoods1", "testgoods2", "testgoods3" ), # Массив с кодами товаров
          'ORDER_PINFO' => array( "", "", "" ), # Массив с описанием товаров
          'ORDER_PRICE' => array( "0.10", "0.11", "0.12" ), # Массив с ценами
          'ORDER_QTY' => array( 1, 2, 1 ),  # Массив с колличеством каждого товара
          'ORDER_VAT' => array( 0, 0, 0 ),  # Массив с указанием НДС для каждого товара
          'ORDER_SHIPPING' => 0 , # Стоимость доставки
          'PRICES_CURRENCY' => "UAH",  # Валюта мерчанта (Внимание! Должно соответствовать валюте мерчанта. )
          'LANGUAGE' => "RU",  
          'BILL_FNAME' => "TEST"
          #.. все остальные параметры
          );
    
    $pay = PayU::getInst()->setOptions( $option )->setData( $forSend )->LU();
    echo $pay; # вывод формы

##Вывод формы после предварительной инициализации
>1.  $pay2 = PayU::getInst();
>    echo $pay2;
>2.  echo PayU::getInst();

-------------------------------------------------

Чтение IPN ответа от сервера PayU
--------

PayU возвращает информацию на IPN только в случае успешной оплаты.

Для этого в качестве ссылки ответа для IPN необходимо указать ссылку вида http://yourdomain.com/form.php?answer=1

    if( isset($_GET['answer']) )
    {
      $payansewer = PayU::getInst()->setOptions( $option )->IPN();
      echo $payansewer;
    
      #----------------
      # оплата успешна.
      # Выполнить доп. операции 
      #----------------
    }

-------------------------------------------------

Проверка ссылки, по которой вернулся клиент (BACK_REF)
--------
Если на сайте используется SSL протокол, указывайте 'https' в качестве параметра метода checkBackRef  : `( ..->checkBackRef('https') )`

    if (isset($_GET['ctrl']))
    {
        $pay = PayU::getInst()->setOptions( $option )->checkBackRef();
        if ( $pay ) echo "Real request";
          else echo "Fake request";
    }
