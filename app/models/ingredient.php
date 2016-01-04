<?php

class Ingredient
{
    public $id, $ingredient_name, $ingredient_type, $price;

    public function __construct($attributes = null)
    {
        foreach ($attributes as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    public static function count_total_price($ingredients)
    {
        $price = 0;

        foreach ($ingredients as $ingredient) {
            $price += $ingredient->price;
        }

        return $price;
    }

    public static function findAll()
    {
        $query = DB::connection()->prepare('SELECT * from Ingredient');

        $query->execute();

        $rows = $query->fetchAll();

        $ingredients = array();

        foreach ($rows as $row) {
            $ingredients[] = new self(array(
                'id' => $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'ingredient_type' => $row['ingredient_type'],
                'price' => $row['price'], ));
        }

        return $ingredients;
    }

    public static function findByType($ingredient_type)
    {
        $query = DB::connection()->prepare('SELECT * from Ingredient '
                .'WHERE ingredient_type = :type');

        $query->execute(array('type' => $ingredient_type));

        $rows = $query->fetchAll();

        $ingredients = array();

        foreach ($rows as $row) {
            $ingredients[] = new self(array(
                'id' => $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'ingredient_type' => $row['ingredient_type'],
                'price' => $row['price'], ));
        }

        return $ingredients;
    }

    public static function findByName($ingredient_name)
    {
        $query = DB::connection()->prepare('SELECT * from Ingredient '
                .'WHERE ingredient_name = :name LIMIT 1');

        $query->execute(array('name' => $ingredient_name));

        $row = $query->fetch();

        if ($row) {
            $ingredient = new self(array(
                'id' => $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'ingredient_type' => $row['ingredient_type'],
                'price' => $row['price'], ));

            return $ingredient;
        }

        return;
    }

    public static function findByCategory($category)
    {
        $cheeses = array();
        $toppings = array();
        $bases = array();
        $sauces = array();

        if ($category != 'Juoma') {
            $toppings = self::findByType('Täyte');
            $sauces = self::findByType('Kastike');

            if ($category == 'Pizza') {
                $cheeses = self::findByType('Juusto');
                $bases = self::findByType('Pizzapohja');
            } elseif ($category == 'Kebab') {
                $cheeses = null;
                $bases = self::findByType('Kebab-lisuke');
            } else {
                $cheeses = null;
                $bases = null;
            }
        }

        return array('cheeses' => $cheeses, 'toppings' => $toppings,
                     'sauces' => $sauces, 'bases' => $bases, );
    }

    public static function findOne($id)
    {
        $query = DB::connection()->prepare('SELECT * from Ingredient '
                .'WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));

        $row = $query->fetch();

        if ($row) {
            $ingredient = new self(array(
                'id' => $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'ingredient_type' => $row['ingredient_type'],
                'price' => $row['price'], ));

            return $ingredient;
        }

        return;
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO Ingredient '
            .'(ingredient_name, ingredient_type, price) '
            .'VALUES (:ingredient_name, :ingredient_type, :price) '
            .'RETURNING id');

        $query->execute(array('ingredient_name' => $this->ingredient_name,
            'ingredient_type' => $this->ingredient_type,
            'price' => $this->price, ));

        $row = $query->fetch();

        $this->id = $row['id'];

        return $this->id;
    }

    public function update()
    {
        $query = DB::connection()->prepare('UPDATE Ingredient '
            .'SET ingredient_name = :ingredient_name, '
                .'ingredient_type = :ingredient_type,'
                .'price = :price '
            .'WHERE id = :id');

        $query->execute(array('ingredient_name' => $this->ingredient_name,
            'ingredient_type' => $this->ingredient_type,
            'price' => $this->price,
            'id' => $this->id, ));
    }

    public function delete()
    {
        $query = DB::connection()->prepare('DELETE FROM Ingredient '
            .'WHERE id = :id '
            .'RETURNING ingredient_name');

        $query->execute(array('id' => $this->id));

        $row = $query->fetch();

        return $row['ingredient_name'];
    }
}
