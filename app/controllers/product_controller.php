<?php

/**
* Handles list, add, delete and edit operations related to products.
*/
class ProductController extends BaseController {  
    
    /**
     * Retrieves all products in a certain category from the database and 
     * displays them.
     * 
     * @param string $category category of the products
     * 
     * @return void
     */
    public static function get_products($category) {
        $products = Product::findByCategory($category);

        foreach($products as $product) {
            $ingredients = Product::findIngredients($product->id);
            
            if ( $product->category != 'Juoma' ) {
                $product->price = Ingredient::count_total_price($ingredients);
            }            
            
            $product->ingredients = $ingredients;
        }  
        
        $ingredients = Ingredient::findByCategory($category);

        View::make("product/list.html", array('products' => $products,
            'ingredients' => $ingredients, 'category' => $category));        
    }
    
    /**
     * Retrieves all ingredients available for products in a certain category.
     * Displays the form for adding a new product.
     * 
     * @param string $category category of the product to add
     * 
     * @return void
     */     
    public static function add_form($category) {
        $ingredients = Ingredient::findByCategory($category);      

        View::make("product/add.html", array('ingredients' => $ingredients,
            'product' => new Product(array('category' => $category))));
    }  
    
    /**
     * Validates data submitted in the add form. If the validation fails,
     * displays the add page. Otherwise, creates a new product, saves it and
     * its ingredients to the database and redirects to the add page.
     * 
     * @return void
     */    
    public static function add() {
        $params = $_POST;

        $attributes = array(
            'product_name' => $params['product_name'],
            'price' => $params['price'],
            'category' => $params['category'],
            'description' => $params['description'],           
        );

        if ( isset($params['customizable']) ) {
            $attributes['customizable'] = 'TRUE';
        } else {
            $attributes['customizable'] = 'FALSE';
        }       
        
        if ( isset($params['ingredients']) ) {
            $attributes['ingredients'] = $params['ingredients'];
        }
        
        $validator = self::product_validator($attributes);      
        
        if ( $validator->validate() ) {
            $product = new Product($attributes);
            
            $product->save();
            
            if ( isset($params['ingredients']) ) {
                self::set_product_ingredients($product, $params['ingredients']); 
            }

            Redirect::to($params['redirect'], 
                    array('message' => 'Tuote lisätty!'));
        } else {
            $ingredients = Ingredient::findByCategory($attributes['category']);

            View::make("product/add.html", array(
                'ingredients' => $ingredients,
                'errors' => $validator->errors(), 
                'product' => $attributes));              
        }        
    }   
    
    /**
     * Retrieves a product, its ingredients and all ingredients available to
     * the product category from the database. Counts the price of the product 
     * and displays the form for editing it.
     * 
     * @param integer $id id of the product to edit 
     * 
     * @return void
     */         
    public static function edit_form($id) {
        $product = Product::findOne($id);
        
        $product_ingredients = Product::findIngredients($id);
        
        foreach($product_ingredients as $product_ingredient) {
            $product->ingredients[] = $product_ingredient->ingredient_name;
        }
        
        if ( $product->category != 'Juoma' ) {
            $product->price = Ingredient::count_total_price($product_ingredients);
        }

        $ingredients = Ingredient::findByCategory($product->category);

        View::make("product/edit.html", array('ingredients' => $ingredients, 
            'product' => $product));        
    }   
    
    /**
     * Validates data submitted in the edit form. If the validation fails,
     * displays the edit page. Otherwise, updates the information in the 
     * database and redirects back to the edit page.
     *  
     * @param integer $id id of the edited product
     * 
     * @return void
     */     
    public static function edit($id){
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'product_name' => $params['product_name'],
            'price' => $params['price'],
            'category' => $params['category'],
            'description' => $params['description']
        );
        
        if ( isset($params['ingredients']) ) {
            $attributes['ingredients'] = $params['ingredients'];
        }        

        if ( isset($params['customizable']) ) {
            $attributes['customizable'] = 'TRUE';
        } else {
            $attributes['customizable'] = 'FALSE';
        }        
        
        $validator = self::product_validator($attributes);    

        if ( $validator->validate() ) {
            $product = new Product($attributes);
            
            $product->update();

            if ( isset($params['ingredients']) ) {
                self::set_product_ingredients($product, $params['ingredients']); 
            }
            
            Redirect::to($params['redirect'], 
                    array('message' => 'Tuotetta muokattu!'));
        } else {
            $ingredients = Ingredient::findByCategory($attributes['category']);

            View::make("product/edit.html", 
                    array('ingredients' => $ingredients, 
                   'errors' => $validator->errors(), 'product' => $attributes));            
        }        
    }   
    
    /**
     * Deletes a product from the database.
     * 
     * @param integer $id id of the product to delete
     * 
     * @return void
     */ 
    public static function delete($id){
        $product = new Product(array('id' => $id));

        $product->delete();      

        Redirect::to($_POST['redirect'], 
                array('message' => 'Tuote poistettu!'));
    }
    
    private static function set_product_ingredients($product, $ingredients) {
        ProductIngredient::deleteByProduct($product->id);
        
        foreach ( $ingredients as $ingredient_name ) {
            if ( $ingredient_name != 'empty' ) {            
                $ingredient = Ingredient::findByName($ingredient_name);

                $product_ingredient = new ProductIngredient(array(
                    'ingredient_id' => $ingredient->id,
                    'product_id' => $product->id               
                ));
                        
                $product_ingredient->save();   
            }                    
        }         
    }    
    
    private static function product_validator($attributes) {
        $validator = new Valitron\Validator($attributes);
        
        $validator->rule('required', 
                         array('product_name', 'category', 'price')); 
        
        $validator->rule('numeric', array('price')); 
        
        return $validator;
    }    
}
