<?php

class ProductIngredient
{
    public $id, $ingredient_id, $product_id;

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
        $query = DB::connection()->prepare('SELECT * from ProductIngredient');

        $query->execute();

        $rows = $query->fetchAll();

        $product_ingredients = array();

        foreach ($rows as $row) {
            $product_ingredients[] = new self(array(
                'id' => $row['id'],
                'ingredient_id' => $row['ingredient_id'],
                'product_id' => $row['product_id'], ));
        }

        return $product_ingredients;
    }

    public static function findByProduct($product_id)
    {
        $query = DB::connection()->prepare('SELECT * from ProductIngredient '
                .'WHERE product_id = :id');

        $query->execute(array('id' => $product_id));

        $rows = $query->fetchAll();

        $product_ingredients = array();

        foreach ($rows as $row) {
            $product_ingredients[] = new self(array(
                'id' => $row['id'],
                'ingredient_id' => $row['ingredient_id'],
                'product_id' => $row['product_id'], ));
        }

        return $product_ingredients;
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO ProductIngredient '
            .'(ingredient_id, product_id) '
            .'VALUES (:ingredient_id, :product_id) '
            .'RETURNING id');

        $query->execute(array('ingredient_id' => $this->ingredient_id,
            'product_id' => $this->product_id, ));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function deleteByProduct($product_id)
    {
        $query = DB::connection()->prepare('DELETE FROM ProductIngredient '
            .'WHERE product_id = :product_id');

        $query->execute(array('product_id' => $product_id));
    }
}
