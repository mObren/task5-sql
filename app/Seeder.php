<?php 
namespace App;

use App\Database;
use App\TablesCreator;
use App\QueryBuilder;

class Seeder {
        private int $numberOfUsers = 20;
        private int $numberOfOrders = 50;
        private int $numberOforderItems = 100;
        private array $usersFirstNames = ['Marko', 'Jovan', 'Ivan', 'Ana', 'Dule', 'Milica', 'Mina', 'Milos', "Marija", "Hari"];
        private array $usersLastNames = ['Markovic', 'Jovanovic', 'Ivanovic', 'Anicic', 'Dulovic', 'Milicic', 'Minic', 'Milosevic', "Marijanovic", "Poter"];
        private array $products = ['Jakna', 'Patike', 'TV', 'Frizider', 'Plivajuci zamajac', 'Majica', 'Telefon', 'Sporet', 'Sladoled'];
        private Database $db;
        private TablesCreator $tableCreator;
        private QueryBuilder $queryBuilder;


        public function __construct() 
        {
            $this->db = new Database;
            $this->tableCreator = new TablesCreator;
            $this->queryBuilder = new QueryBuilder;
        }

        private function generateRandomDate(): string
        {
        $timestamp = rand( strtotime("31.07.2022."), strtotime("NOW") );
        return date("Y-m-d h:i:s", $timestamp );
        }

        public function seedUsersTable(int $quantity,array $firstNames,array $lastNames): void
        {
            echo "Seeding users table... \n";
            $sql = "INSERT INTO users (first_name, last_name, email, phone, created_at, updated_at) VALUES ";
            for ($i=0; $i < $quantity; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $email = strtolower("$firstName.$lastName$i@gmail.com");
                $phone = "+3816" . rand(400000, 9999999);
                $date = $this->generateRandomDate();
                $sql .= "('$firstName', '$lastName', '$email', '$phone', '$date', '$date'),";
            }
            $sql = rtrim($sql, ",");
            $sql.= ";";
            $this->db->write($sql);
            echo "Users table is seeded. \n";
        }

        public function seedOrdersTable(int $quantity): void 
        {
            echo "Seeding orders table... \n";
            $sql = "INSERT INTO orders (user_id, value, created_at, updated_at) VALUES ";
            for ($i=0; $i < $quantity; $i++) {
                $date = $this->generateRandomDate();
                $users = $this->getAllTablesIds('users');
                $userId = $users[array_rand($users)];
                $value = $this->getRandomFloat(1, 150000, 2);
                $sql .= "($userId, $value, '$date', '$date'),";
            }
            $sql = rtrim($sql, ",");
            $sql.= ";";
            $this->db->write($sql);
            echo "Orders table is seeded. \n";
        }

        public function seedProductsTable()
        {
            echo "Seeding products table... \n";
            $sql = "INSERT INTO products (name, price) VALUES ";
            foreach ($this->products as $product) {
                $price = $this->getRandomFloat(1, 50000, 2);

                $sql .= "('$product', $price),";
            }
            $sql = rtrim($sql, ",");
            $sql.= ";";
            $this->db->write($sql);
            echo "Products table is seeded. \n";
        }

        public function seedOrderItemsTable(int $quantity): void 
        {
            echo "Seeding order_items table... \n";
            $sql = "INSERT INTO order_items (order_id, product_id) VALUES ";
            for ($i=0; $i < $quantity; $i++) {
                $orders = $this->getAllTablesIds('orders');
                $orderId = $orders[array_rand($orders)];
                $products = $this->getAllTablesIds('products');
                $productId = $products[array_rand($products)];
                $sql .= "($orderId, $productId),";
            }
            $sql = rtrim($sql, ",");
            $sql.= ";";
            $this->db->write($sql);
            echo "Order_items table is seeded. \n";
        }
    

        public function getAllTablesIds(string $tableName): array
        {
            $rows = $this->queryBuilder->from($tableName)->columns('id')->get();
            $ids = [];
            foreach ($rows as $row) {
                $ids[] = $row->id;
            }
            return $ids;
        }

        public function getRandomFloat(int $min, int $max, int $decimals = 2): float
        {
        $scale = pow(10, $decimals);
        return mt_rand($min * $scale, $max * $scale) / $scale;
        }

        public function seedDatabase(): void
        {
            $this->tableCreator->createTables();
            $this->seedUsersTable($this->numberOfUsers, $this->usersFirstNames, $this->usersLastNames);
            $this->seedOrdersTable($this->numberOfOrders);
            $this->seedProductsTable();
            $this->seedOrderItemsTable($this->numberOforderItems);
        }

}



