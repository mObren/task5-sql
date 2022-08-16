<?php
require_once "vendor/autoload.php";

use App\Queries;

$queries = new Queries;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 5 - SQL</title>

    <style>
        .task {
            color: blue;
        }
    </style>
</head>
<body>
    <h1>Task 5 - SQL</h1>
    <div>
        <p class="task">a) Prikažite sve korisnike koji su se prijavili u prethodna dva dana</p>
    </div>
    <div>
        <ul>
        <?php
         if (!empty($queries->allUsersInLastTwoDays())) {
            foreach ($queries->allUsersInLastTwoDays() as $user) { ?>
            <li> <?php echo "Ime i prezime: $user->first_name $user->last_name - Email: $user->email - Broj telefona : $user->phone - Prijavljen : $user->created_at"?> </li>
            <?php } 
            } else  {
                echo "Nema korisnika koji su se prijavili u poslednja dva dana.";
            } ?>
        </ul>

    </div>
        <div>
        <p class="task">b) Prikažite ime i prezime korisnika, id porudžbine i ukupnu vrednost svih porudžbina</p>
    </div>
    <div>
        <p>Ukpuna vrednost porudzbina : <?php echo $queries->sumOfAllOrdersValues(); ?> </p>
        <ul>
        <?php
         if (!empty($queries->usersWithOrders())) {
            foreach ($queries->usersWithOrders() as $row) { ?>
            <li> <?php echo "ID porudzbine : $row->orderId - Ime i prezime: $row->first_name $row->last_name"?> </li>
            <?php } 
            } else  {
                echo "Nema rekorda u bazi.";
            } ?>
        </ul>

    </div>
     <div>
        <p class="task">c) Prikažite sve korisnike koji su imali najmanje dve porudžbine </p>
    </div>
    <div>
        <ul>
        <?php
         if (!empty($queries->usersWithAtLeastTwoOrders())) {
            foreach ($queries->usersWithAtLeastTwoOrders() as $user) { ?>
            <li> <?php echo "Ime i prezime: $user->first_name $user->last_name - Ukupno porudzbina: $user->total"?> </li>
            <?php } 
            } else  {
                echo "Nema korisnika koji imaju vise od dve porudzbine.";
            } ?>
        </ul>

    </div>
    <div>
        <p class="task">d) Prikažite ime I prezime korisnika, id porudžbine I broj stavki za svaku porudžbinu</p>
    </div>
    <div>
        <ul>
        <?php
         if (!empty($queries->ordersWithCountedProducts())) {
            foreach ($queries->ordersWithCountedProducts() as $order) { ?>
            <li> <?php echo "ID porudzbine: $order->orderId - Ime i prezime korisnika: $order->first_name $order->last_name - Ukupno stavki: $order->totalProducts"?> </li>
            <?php } 
            } else  {
                echo "Nema rekorda u bazi.";
            } ?>
        </ul>

    </div>
    <div>
        <p class="task">e) Prikažite ime I prezime korisnika, id porudžbine koja ima najmanje dve stavke</p>
    </div>
    <div>
        <ul>
        <?php
         if (!empty($queries->ordersWithAtLeastTwoProducts())) {
            foreach ($queries->ordersWithAtLeastTwoProducts() as $order) { ?>
            <li> <?php echo "ID porudzbine: $order->orId - Ime i prezime korisnika: $order->fname $order->lname"?> </li>
            <?php } 
            } else  {
                echo "Nema porudzbina koje imaju vise od dva proizvoda.";
            } ?>
        </ul>

    </div>
        <div>
        <p class="task">f)  Prikažite sve korisnike koji su kupili najmanje tri različita proizvoda u svim porudžbinama</p>
    </div>
    <div>
        <ul>
        <?php
         if (!empty($queries->usersWhoBoughtAtLeastThreeProducts())) {
            foreach ($queries->usersWhoBoughtAtLeastThreeProducts() as $user) { ?>
            <li> <?php echo "Ime i prezime korisnika: $user->fname $user->lname <p>Email: $user->mail - Broj telfona: $user->number</p>"?> </li>
            <?php } 
            } else  {
                echo "Nema korisnika koji su kupili vise od tri razlicita proizvoda.";
            } ?>
        </ul>

    </div>
</body>
</html>
