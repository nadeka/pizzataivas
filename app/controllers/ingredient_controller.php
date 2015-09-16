<?php

/**
* Handles list, add, delete and edit operations related to ingredients.
*/
class IngredientController extends BaseController {

    /**
     * Retrieves all ingredients from the database and displays them.
     * 
     * @return void
     */    
    public static function get_ingredients() {
        $ingredients = Ingredient::findAll();
        
        View::make("ingredient/list.html", 
                    array('ingredients' => $ingredients));
    }
    
    /**
     * Displays the form for adding a new ingredient.
     * 
     * @return void
     */      
    public static function add_form() {
        View::make("ingredient/add.html");
    }    

    /**
     * Validates data submitted in the add form. If the validation fails,
     * displays the add page. Otherwise, creates a new ingredient, saves it to 
     * the database and redirects to the add page.
     * 
     * @return void
     */  
    public static function add() {
        $params = $_POST;

        $attributes = array(
            'ingredient_name' => $params['ingredient_name'],
            'ingredient_type' => $params['ingredient_type'],
            'price' => $params['price']
        );

        $validator = self::ingredient_validator($attributes);  
        
        if ( $validator->validate() ) {
            $ingredient = new Ingredient($attributes);
            
            $ingredient->save();
            
            Redirect::to($params['redirect'], array(
                'message' => 'Ainesosa lisätty!'));
        } else {
            View::make('ingredient/add.html', 
                        array ('errors' => $validator->errors()
                             , 'ingredient' => $attributes));
        }
    }
    
    /**
     * Retrieves an ingredient from the database and displays the form for 
     * editing it.
     * 
     * @param $id id of the ingredient to edit 
     * 
     * @return void
     */     
    public static function edit_form($id) {
        $ingredient = Ingredient::findOne($id);
        
        View::make("ingredient/edit.html", 
                   array('ingredient' => $ingredient));
    }
    
    /**
     * Validates data submitted in the edit form. If the validation fails,
     * displays the edit page. Otherwise, updates the information in the 
     * database and redirects back to the edit page.
     *  
     * @param integer $id id of the edited ingredient
     * 
     * @return void
     */  
    public static function edit($id){
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'ingredient_name' => $params['ingredient_name'],
            'ingredient_type' => $params['ingredient_type'],
            'price' => $params['price']
        );
 
        $validator = self::ingredient_validator($attributes);
        
        if ( $validator->validate() ) {
            $ingredient = new Ingredient($attributes);
            
            $ingredient->update();
            
            Redirect::to($params['redirect'], array(
                'message' => 'Ainesosaa muokattu!'));
        } else {
            View::make('ingredient/edit.html', array(
                                  'errors' => $validator->errors()
                                , 'ingredient' => $attributes));
        }        
    }  
    
    /**
     * Deletes an ingredient from the database.
     * 
     * @param integer $id id of the ingredient to delete
     * 
     * @return void
     */ 
    public static function delete($id){
        $ingredient = new Ingredient(array('id' => $id));

        $ingredient_name = $ingredient->delete();

        Redirect::to('/ainesosat', 
                array('message' => $ingredient_name . ' poistettu!'));
    }
        
    private static function ingredient_validator($attributes) {
        $validator = new Valitron\Validator($attributes);
        
        $validator->rule('required', array(
            'ingredient_name', 'ingredient_type', 'price'));
        
        $validator->rule('numeric', 'price');
        
        return $validator;
    }    
}