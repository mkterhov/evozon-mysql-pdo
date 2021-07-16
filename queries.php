<?php
/*Current trainee is michael

Problem suggested by Alexei:
1 -> Select all names of products from orders with order number = 10422
Problem suggested by razvan:
5 -> Display first 15 customers payments from Boston after 2003-12-31
Problem suggested by Mihai:
10 -> Show customers firstname, lastname, phone, order number and status, ordered descended by required date,  where status is set to In process*/
$pass = "";
$user = "";
$pdo = new PDO('mysql:host=localhost;dbname=classicmodels', $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//1 -> Select all names of products from orders with order number = 10422
$sth = $pdo->prepare("SELECT productName FROM products inner join (select productCode from orderdetails WHERE orderNumber =  10422) as od on products.productCode = od.productCode");
$sth->execute();
/* Fetch all of the remaining rows in the result set */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
//print_r($result);

//5 -> Display first 15 customers payments from Boston after 2003-12-31
$town = "Boston";
$date = "2003-12-31";
$sth = $pdo->prepare("SELECT c.customerName, c.a FROM
              payments inner join
              (select customerName,city,state,country, customers) as c 
                  ON payments.customerNumber = c.customerNumber 
                WHERE payments.paymentDate > ? AND c.city = ?");
//(select * from customers WHERE city=?)
//$sth = $pdo->prepare("select * from customers WHERE city = 'Boston' AND c.city = ");


/*where payment.paymentDate > ? ORDER BY payment.paymentDate DESC LIMIT 15");*/
$sth->bindValue(1, $date, PDO::PARAM_STR);
$sth->bindValue(2, $town, PDO::PARAM_STR);


//10 -> Show customers firstname, lastname, phone, order number and status, ordered descended by required date,  where status is set to In proces

//$sth = $pdo->prepare("SELECT c.customerName, c.phone,o.status,o.requiredDate,o.orderNumber FROM
//              customers as c inner join
//              (select status,requiredDate,orderNumber,customerNumber from orders) as o
//                  ON c.customerNumber = o.customerNumber where o.status ='In Process' ORDER BY o.requiredDate DESC");
//$sth = $pdo->prepare("select status,requiredDate,orderNumber,customerNumber from orders");
//
$sth->execute();
/* Fetch all of the remaining rows in the result set */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
