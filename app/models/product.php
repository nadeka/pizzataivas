<?php

class Product {
    
    public $id, $product_name, $price, $category,
           $customizable, $description, $ingredients;
    
    public function __construct($attributes = null) {
         foreach($attributes as $attribute => $value) {
             if ( property_exists($this, $attribute) ) {
                 $this->{$attribute} = $value;
             }
         }
    }
   
    public static function findAll() {
        $query = DB::connection()->prepare('SELECT * from Product');
        
        $query->execute();
        
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
    
    public static function findByName($product_name) {
        $query = DB::connection()->prepare('SELECT * from Product '
                . 'WHERE product_name = :name');
        
        $query->execute(array('name' => $product_name));
        
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
    
    public static function findByCategory($category) {
        $query = DB::connection()->prepare('SELECT * from Product '
                . 'WHERE category = :category');
        
        $query->execute(array('category' => $category));
        
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
        $query = DB::connection()->prepare('SELECT * from Product '
                . 'WHERE id = :id LIMIT 1');
        
        $query->execute(array('id' => $id));
        
        $row = $query->fetch();
        
        if ( $row ) {
            $product = new Product(array(
               'id' => $row['id'],
               'product_name' => $row['product_name'],
               'price' => $row['price'],
               'category' => $row['category'],
               'customizable' => $row['customizable'],
               'description' => $row['description']));
            
            return $product;
        }
        
        return null;
    }
    
    public static function findIngredients($id) {
        $query = DB::connection()->prepare('SELECT * from Ingredient '
                . 'INNER JOIN ProductIngredient '
                . 'ON ProductIngredient.ingredient_id = Ingredient.id '
                . 'WHERE ProductIngredient.product_id = :id');
        
        $query->execute(array('id' => $id));
        
        $rows = $query->fetchAll();
        
        $ingredients = array();
        
        foreach ($rows as $row) {
            $ingredients[] = new Ingredient(array(
                'id' => $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'ingredient_type' => $row['ingredient_type'],
                'price' => $row['price']));  
        }
        
        return $ingredients;
    }  
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Product '
            . '(product_name, price, category, customizable, description) '
            . 'VALUES (:product_name, :price, :category, :customizable, '
            . ':description) '
            . 'RETURNING id');
      
        $query->execute(array('product_name' => $this->product_name, 
            'price' => $this->price,
            'category' => $this->category, 
            'customizable' => $this->customizable, 
            'description' => $this->description));
      
        $row = $query->fetch();

        $this->id = $row['id'];
        
        return $this->id;
    } 
    
    public function update(){
        $query = DB::connection()->prepare('UPDATE Product '
            . 'SET product_name = :product_name,'
                . 'price = :price,'
                . 'customizable = :customizable,'
                . 'description = :description '
            . 'WHERE id = :id');
      
        $query->execute(array('product_name' => $this->product_name, 
            'price' => $this->price,
            'customizable' => $this->customizable,
            'description' => $this->description,
            'id' => $this->id));
    }
    
    public function delete(){
        $query = DB::connection()->prepare('DELETE FROM Product '
            . 'WHERE id = :id');
      
        $query->execute(array('id' => $this->id));
    }    
}