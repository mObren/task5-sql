<?php

namespace App;

use App\Database;
class TablesCreator {

    protected $query = 'CREATE TABLE if not exists users
        (
            id int auto_increment primary key,
            first_name varchar(50) null,
            last_name varchar(50) null,
            phone varchar(50) null,
            email varchar(100) null,
            created_at datetime default current_timestamp() null,
            updated_at datetime default current_timestamp() null
        );
        CREATE TABLE if not exists orders
        (
            id int auto_increment primary key,
            user_id int,
            value decimal(12, 2),
            created_at datetime default current_timestamp() null,
            updated_at datetime default current_timestamp() null,
            constraint orders_users_id_fk
            foreign key (user_id) references users (id)
            on update cascade on delete cascade
        );
        CREATE TABLE if not exists products
        (
            id int auto_increment primary key,
            name varchar(255) null,
            price decimal(10, 2),
            created_at datetime default current_timestamp() null,
            updated_at datetime default current_timestamp() null
        );
        CREATE TABLE if not exists order_items
        (
            id int auto_increment primary key,
            order_id int,
            product_id int,
            created_at datetime default current_timestamp() null,
            updated_at datetime default current_timestamp() null,
            constraint order_items_orders_id_fk
            foreign key (order_id) references orders (id)
            on update cascade on delete cascade,
            constraint order_items_products_id_fk
            foreign key (product_id) references products (id)
            on update cascade on delete cascade

        );';


    public function createTables() {
        $db = new Database;
        $db->write($this->query);
        echo "Tables are created. \n";
    }
}