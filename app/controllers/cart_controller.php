<?php

/**
 * Handles add and delete operations related to the shopping cart.
 */
class CartController extends BaseController
{
    /**
     * Retrieves the item to add from the database. If the product is
     * customizable, adds the selected ingredients to an array. Saves the 
     * product to the 'cart items' session array as a JSON string. Adds the 
     * price of the product to the total cart price. Redirects to the current 
     * page.
     * 
     * @param int $id id of the product to add
     */
    public static function add($id)
    {
        $product = Product::findOne($id);

        $params = $_POST;

        if ($product->customizable) {
            $product = new Product(array(
                'id' => $id,
                'product_name' => $product->product_name,
                'category' => $product->category,
                'customizable' => $product->customizable,
                'description' => $product->description,
                'ingredients' => array(),
            ));

            if (isset($params['ingredients'])) {
                self::set_product_ingredients($product, $params['ingredients']);
            }

            if (count($product->ingredients) == 0) {
                Redirect::to($params['redirect'],
                        array('message' => 'Valitse ainekset!'));
            }
        } else {
            $product->ingredients = Product::findIngredients($product->id);
        }

        if ($product->category != 'Juoma') {
            $product->price = Ingredient::count_total_price($product->ingredients);
        }

        $_SESSION['cart']['items'][] = json_encode($product);

        $_SESSION['cart']['price'] += $product->price;

        Redirect::to($params['redirect'],
               array('message' => $product->product_name.' lisätty koriin!'));
    }

    /**
     * Deletes a product from the cart and substracts its price from the total
     * cart price. Redirects to the current page.
     * 
     * @param int $id id of the product to delete
     */
    public static function delete($id)
    {
        $item = json_decode($_SESSION['cart']['items'][$id]);

        unset($_SESSION['cart']['items'][$id]);

        $_SESSION['cart']['price'] -= $item->price;

        $_SESSION['cart']['items'] =
                array_values($_SESSION['cart']['items']);

        Redirect::to($_POST['redirect'],
            array('message' => $item->product_name
                .' poistettu korista!', ));
    }

    /**
     * Deletes all products from the cart by clearing the 'cart' session
     * variable. Redirects to the current page.
     */
    public static function deleteAll()
    {
        $_SESSION['cart'] = array('items' => array(), 'price' => 0.0);

        Redirect::to($_POST['redirect'],
                array('message' => 'Kori tyhjennetty!'));
    }

    private static function set_product_ingredients($product, $ingredients)
    {
        foreach ($ingredients as $ingredient_name) {
            if ($ingredient_name != 'empty') {
                $product->ingredients[] =
                        Ingredient::findByName($ingredient_name);
            }
        }
    }
}
