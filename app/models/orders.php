<?php

class Orders {
    
    public $id, $user_id, $order_time, $agreed_delivery_time,
            $actual_delivery_time, $delivery_address, $delivery_method, 
            $total_price, $status, $user_info;
    
    public function __construct($attributes = null) {
         foreach($attributes as $attribute => $value) {
             if ( property_exists($this, $attribute) ) {
                 $this->{$attribute} = $value;
             }
         }
    }    
    
    public static function findAll() {
        $query = DB::connection()->prepare('SELECT * from Orders');
        
        $query->execute();
        
        $rows = $query->fetchAll();
        
        $orders = array();
        
        foreach ($rows as $row) {
            $orders[] = new Orders(array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'order_time' => $row['order_time'],
                'agreed_delivery_time' => $row['agreed_delivery_time'],
                'actual_delivery_time' => $row['actual_delivery_time'],
                'delivery_address' => $row['delivery_address'],
                'delivery_method' => $row['delivery_method'],
                'total_price' => $row['total_price'],
                'status' => $row['status'],
                'user_info' => $row['user_info']
            ));
        }
        
        return $orders;
    }
    
    public static function findByUser($user_id) {
        $query = DB::connection()->prepare('SELECT * from Orders '
                . 'WHERE user_id = :user_id');
        
        $query->execute(array('user_id' => $user_id));
        
        $rows = $query->fetchAll();
        
        $orders = array();
        
        foreach ($rows as $row) {
            $orders[] = new Orders(array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'order_time' => $row['order_time'],
                'agreed_delivery_time' => $row['agreed_delivery_time'],
                'actual_delivery_time' => $row['actual_delivery_time'],
                'delivery_address' => $row['delivery_address'],
                'delivery_method' => $row['delivery_method'],
                'total_price' => $row['total_price'],
                'status' => $row['status'],
                'user_info' => $row['user_info']));
        }
        
        return $orders;
    } 
    
    public static function findProducts($id) {
        $query = DB::connection()->prepare('SELECT * from Product '
                . 'INNER JOIN OrderProduct '
                . 'ON OrderProduct.product_id = Product.id '
                . 'WHERE OrderProduct.order_id = :id');
        
        $query->execute(array('id' => $id));
        
        $rows = $query->fetchAll();
        
        $products = array();
        
        foreach ($rows as $row) {
            $products[] = new Product(array(
                'id' => $row['id'],
                'product_name' => $row['product_name'],
                'price' => $row['price'],
                'category' => $row['category'],
                'customizable' => $row['customizable'],
                'description' => $row['description']));  
        }
        
        return $products;
    }     
    
    public static function findOne($id) {
        $query = DB::connection()->prepare('SELECT * from Orders '
                . 'WHERE id = :id LIMIT 1');
        
        $query->execute(array('id' => $id));
        
        $row = $query->fetch();
        
        if ( $row ) {
            $order = new Orders(array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'order_time' => $row['order_time'],
                'agreed_delivery_time' => $row['agreed_delivery_time'],
                'actual_delivery_time' => $row['actual_delivery_time'],
                'delivery_address' => $row['delivery_address'],
                'delivery_method' => $row['delivery_method'],
                'total_price' => $row['total_price'],
                'status' => $row['status'],
                'user_info' => $row['user_info']));
            
            return $order;
        }
        
        return null;
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Orders '
            . '(user_id, order_time, agreed_delivery_time, '
            . 'actual_delivery_time, delivery_address, delivery_method, '
            . 'total_price, status, user_info) '
            . 'VALUES (:user_id, :order_time, :agreed_delivery_time, '
            . ':actual_delivery_time, :delivery_address, :delivery_method, '
            . ':total_price, :status, :user_info) '
            . 'RETURNING id');
      
        $query->execute(array('user_id' => $this->user_id, 
            'order_time' => $this->order_time, 
            'agreed_delivery_time' => $this->agreed_delivery_time, 
            'actual_delivery_time' => $this->actual_delivery_time, 
            'delivery_address' => $this->delivery_address,
            'delivery_method' => $this->delivery_method,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'user_info' => $this->user_info));
      
        $row = $query->fetch();

        $this->id = $row['id'];
        
        return $this->id;
    } 
    
    public function update(){
        $query = DB::connection()->prepare('UPDATE Orders '
            . 'SET actual_delivery_time = :actual_delivery_time, '
            . 'status = :status '
            . 'WHERE id = :id');
      
        $query->execute(array('actual_delivery_time' => 
                               $this->actual_delivery_time, 
            'status' => $this->status,
            'id' => $this->id));
    }   
}
