<?php

class OrderProduct
{
    public $id, $product_id, $order_id, $product_info;

    public function __construct($attributes = null)
    {
        foreach ($attributes as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    public static function findAll()
    {
        $query = DB::connection()->prepare('SELECT * from OrderProduct');

        $query->execute();

        $rows = $query->fetchAll();

        $order_products = array();

        foreach ($rows as $row) {
            $order_products[] = new self(array(
                'id' => $row['id'],
                'product_id' => $row['product_id'],
                'order_id' => $row['order_id'],
                'product_info' => $row['product_info'], ));
        }

        return $order_products;
    }

    public static function findByProduct($product_id)
    {
        $query = DB::connection()->prepare('SELECT * from OrderProduct '
                .'WHERE product_id = :id');

        $query->execute(array('id' => $product_id));

        $rows = $query->fetchAll();

        $order_products = array();

        foreach ($rows as $row) {
            $order_products[] = new self(array(
                'id' => $row['id'],
                'product_id' => $row['product_id'],
                'order_id' => $row['order_id'],
                'product_info' => $row['product_info'], ));
        }

        return $order_products;
    }

    public static function findByOrder($order_id)
    {
        $query = DB::connection()->prepare('SELECT * from OrderProduct '
                .'WHERE order_id = :id');

        $query->execute(array('id' => $order_id));

        $rows = $query->fetchAll();

        $order_products = array();

        foreach ($rows as $row) {
            $order_products[] = new self(array(
                'id' => $row['id'],
                'product_id' => $row['product_id'],
                'order_id' => $row['order_id'],
                'product_info' => $row['product_info'], ));
        }

        return $order_products;
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO OrderProduct '
                .'(product_id, order_id, product_info) '
                .'VALUES (:product_id, :order_id, :product_info) '
                .'RETURNING id');

        $query->execute(array(
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
            'product_info' => $this->product_info, ));

        $row = $query->fetch();

        $this->id = $row['id'];
    }
}
