<?php

class Users
{
    public $id, $username, $password, $first_name, $last_name, $email,
            $phone_number, $street_address, $postal_code, $city;

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
        $query = DB::connection()->prepare('SELECT * from Users');

        $query->execute();

        $rows = $query->fetchAll();

        $users = array();

        foreach ($rows as $row) {
            $users[] = new self(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'street_address' => $row['street_address'],
                'postal_code' => $row['postal_code'],
                'city' => $row['city'], ));
        }

        return $users;
    }

    public static function findOne($id)
    {
        $query = DB::connection()->prepare('SELECT * from Users '
                .'WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $user = new self(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'street_address' => $row['street_address'],
                'postal_code' => $row['postal_code'],
                'city' => $row['city'], ));

            return $user;
        }

        return;
    }

    public static function findByUsername($username)
    {
        $query = DB::connection()->prepare('SELECT * from Users '
                .'WHERE username = :username');

        $query->execute(array('username' => $username));

        $rows = $query->fetchAll();

        $users = array();

        foreach ($rows as $row) {
            $users[] = new self(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'street_address' => $row['street_address'],
                'postal_code' => $row['postal_code'],
                'city' => $row['city'], ));
        }

        return $users;
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO Users '
            .'(username, password, first_name, last_name, email,'
            .'phone_number, street_address, postal_code, city) '
            .'VALUES (:username, :password, :first_name, :last_name, :email,'
            .':phone_number, :street_address, :postal_code, :city)'
            .'RETURNING id');

        $query->execute(array('username' => $this->username,
            'password' => $this->password,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'street_address' => $this->street_address,
            'postal_code' => $this->postal_code,
            'city' => $this->city, ));

        $row = $query->fetch();

        $this->id = $row['id'];

        return $this->id;
    }

    public function update()
    {
        $query = DB::connection()->prepare('UPDATE Users '
            .'SET first_name = :first_name,'
                .'last_name = :last_name,'
                .'email = :email,'
                .'phone_number = :phone_number,'
                .'street_address = :street_address,'
                .'postal_code = :postal_code,'
                .'city = :city '
            .'WHERE id = :id');

        $query->execute(array(
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'street_address' => $this->street_address,
            'postal_code' => $this->postal_code,
            'city' => $this->city, ));
    }

    public static function authenticate($username, $password)
    {
        $query = DB::connection()->prepare('SELECT * from Users '
               .'WHERE username = :username LIMIT 1');

        $query->execute(array('username' => $username));

        $row = $query->fetch();

        if ($row && $row['password'] == crypt($password, 1)) {
            $user = new self(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'street_address' => $row['street_address'],
                'postal_code' => $row['postal_code'],
                'city' => $row['city'], ));

            return $user;
        } else {
            return;
        }
    }
}
