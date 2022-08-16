<?php
namespace App;

use App\QueryBuilder;
class Queries {

    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder;
    }

    //a) 
    public function allUsersInLastTwoDays(): array
    {
        

        $twoDaysAgo = date("Y-m-d h:i:s" , strtotime("NOW - 2 days"));
        return $this->queryBuilder->from('users')->columns("*")->where("created_at >= :created_at")->get(['created_at' => $twoDaysAgo]);
    }

    //b.1)
    public function usersWithOrders(): array
    {
        

        return $this->queryBuilder
        ->from('orders')
        ->columns("orders.id as orderId", "first_name", "last_name")
        ->join("INNER", 'users', 'orders.user_id = users.id')
        ->get();
    }
    //b.2)
    public function sumOfAllOrdersValues(): string
    {
        
        return $this->queryBuilder->from('orders')->columns("SUM(value) as total_value")->get()[0]->total_value;
    }

    //c)
    public function usersWithAtLeastTwoOrders(): array
    {
        $helper = new QueryBuilder;
        $aliasTable = $helper->from('orders')
        ->columns("first_name", "last_name", "COUNT(orders.id) as total")
        ->join('INNER', 'users', 'users.id = orders.user_id')->groupBy('orders.user_id');
        

        return $this->queryBuilder->from("($aliasTable) as alias")->columns("*")->where('alias.total >= 2')->get();
    }

    //d
    public function ordersWithCountedProducts()
    {

        return $this->queryBuilder->from("orders")
        ->columns("first_name", "last_name", "orders.id as orderId", "COUNT(order_items.id) as totalProducts")
        ->join("LEFT", "order_items", "orders.id = order_items.order_id")
        ->join("INNER", "users", "users.id = orders.user_id")
        ->groupBy("orders.id")
        ->get();
    }

    //e
    public function ordersWithAtLeastTwoProducts() 
    {

        $helper = new QueryBuilder;
        $aliasTable = $helper->from("orders")
        ->columns("first_name", "last_name", "orders.id as orderId", "COUNT(order_items.id) as totalProducts")
        ->join("LEFT", "order_items", "orders.id = order_items.order_id")
        ->join("INNER", "users", "users.id = orders.user_id")
        ->groupBy("orders.id");

        return $this->queryBuilder
        ->from("($aliasTable) as alias")
        ->columns("alias.first_name as fname", "alias.last_name as lname", "alias.orderId as orId")
        ->where("alias.totalProducts >= 2")
        ->get();
    }

    public function usersWhoBoughtAtLeastThreeProducts()
    {
        $helper = new QueryBuilder;
        $aliasTable = $helper->from("orders")
        ->columns("first_name", "last_name", "email","phone","orders.id as orderId", "COUNT(DISTINCT(order_items.product_id)) as totalProducts")
        ->join("LEFT", "order_items", "orders.id = order_items.order_id")
        ->join("INNER", "users", "users.id = orders.user_id")
        ->groupBy("orders.id");

        return $this->queryBuilder
        ->from("($aliasTable) as alias")
        ->columns("alias.first_name as fname", "alias.last_name as lname", "alias.email as mail", "alias.phone as number")
        ->where("alias.totalProducts >= 3")
        ->get();
    }
}