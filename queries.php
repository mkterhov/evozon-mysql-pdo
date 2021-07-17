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

echo "-> Select all names of products from orders with order number = 10422";
$orderNumber = 10422;
$sth = $pdo->prepare("
    SELECT productName FROM products
    inner join
    (select productCode from orderdetails WHERE orderNumber =  ?) as od
        on products.productCode = od.productCode
");
$sth->bindValue(1, $orderNumber, PDO::PARAM_INT);

$sth->execute();
/* Fetch all of the remaining rows in the result set */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
print_r($result);

echo "Display first 15 customers payments from Boston after 2003-12-31";
$town = "Boston";
$date = "2003-12-31";
$sth = $pdo->prepare("
                SELECT c.customerName, c.city, p.amount,p.paymentDate 
                FROM payments as p
                inner join (
                select customerNumber,customerName,city 
                from customers) as c 
                ON p.customerNumber = c.customerNumber
                WHERE p.paymentDate > ? AND c.city = ?
                ORDER BY p.paymentDate DESC LIMIT 15"
);

$sth->bindValue(1, $date);
$sth->bindValue(2, $town);
$sth->execute();
/* Fetch all of the remaining rows in the result set */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
print_r($result);

echo "-> Show customers firstname, lastname, phone, order number and status, ordered descended by required date,  where status is set to In process";
//TODO: simplify the derived selects
$orderStatus = 'In Process';
$sth = $pdo->prepare("SELECT c.customerName, c.phone,o.orderNumber, o.status, o.requiredDate FROM
              customers as c inner join
              (select status,requiredDate,orderNumber,customerNumber from orders) as o
                  ON c.customerNumber = o.customerNumber 
                where o.status = ?
                ORDER BY o.requiredDate DESC
");
$sth->bindValue(1, $orderStatus);
$sth->execute();
/* Fetch all of the remaining rows in the result set */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
