<?php

/**
 * Handles add and edit operations related to users.
 */
class UsersController extends BaseController
{
    /**
     * Displays the login page.
     */
    public static function login_form()
    {
        View::make('user/login.html');
    }

    /**
     * Authenticates the user. If the authentication fails, displays the login 
     * page. Otherwise, sets the user id to the 'user' session variable and 
     * redirects to checkout. 
     */
    public static function login()
    {
        $params = $_POST;

        $user = Users::authenticate($params['username'],
                                           $params['password']);

        if ($user) {
            $_SESSION['user'] = $user->id;

            Redirect::to('/kassa',
                         array('message' => 'Kirjautuminen onnistui!'));
        } else {
            View::make('user/login.html', array('message' => 'Väärä käyttäjätunnus tai salasana!',
                'username' => $params['username'], ));
        }
    }

    /**
     * Logs the user out by setting the 'user' session variable to null. 
     * Redirects to the login page.
     */
    public static function logout()
    {
        $_SESSION['user'] = null;

        Redirect::to('/kirjaudu', array('message' => 'Olet kirjautunut ulos!'));
    }

    /**
     * Displays the register page.
     */
    public static function add_form()
    {
        View::make('user/add.html');
    }

    /**
     * Validates data submitted in the register form. If the validation fails,
     * displays the register page. Otherwise, encrypts the password, creates
     * a new user, saves the user to the database and session and redirects to 
     * checkout.
     */
    public static function add()
    {
        $params = $_POST;

        $attributes = array(
            'username' => $params['username'],
            'password' => $params['password'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'phone_number' => $params['phone_number'],
            'street_address' => $params['street_address'],
            'postal_code' => $params['postal_code'],
            'city' => $params['city'],
            'bonus_amount' => 0.0, );

        $validator = self::user_validator($attributes, 'register');

        if (count(Users::findByUsername($attributes['username'])) > 0) {
            View::make('user/add.html', array('message' => 'Käyttäjätunnus on varattu!', 'attributes' => $attributes));
        }

        if ($validator->validate()) {
            $password = crypt($attributes['password'], CRYPT_BLOWFISH);

            $attributes['password'] = $password;

            $user = new Users($attributes);

            $user_id = $user->save();

            $_SESSION['user'] = $user_id;

            Redirect::to('/kassa',
                         array('message' => 'Rekisteröityminen onnistui!'));
        } else {
            View::make('user/add.html', array('errors' => $validator->errors(), 'attributes' => $attributes));
        }
    }

    /**
     * Retrieves the currently authenticated user from the 
     * database and displays the form for editing their information.
     */
    public static function edit_form()
    {
        $user = Users::findOne($_SESSION['user']);

        View::make('user/edit.html', array('user' => $user));
    }

    /**
     * Validates data submitted in the edit form. If the validation fails,
     * displays the edit page. Otherwise, updates the information in the 
     * database and redirects to the edit page.
     */
    public static function edit()
    {
        $params = $_POST;

        $attributes = array(
            'id' => $params['user_id'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'phone_number' => $params['phone_number'],
            'street_address' => $params['street_address'],
            'postal_code' => $params['postal_code'],
            'city' => $params['city'], );

        $validator = self::user_validator($attributes, 'user_info');

        if ($validator->validate()) {
            $user = new Users($attributes);

            $user->update();

            Redirect::to($params['redirect'], array(
                'message' => 'Tiedot tallennettu!', ));
        } else {
            View::make('user/edit.html',
                array('errors' => $validator->errors(), 'user' => $attributes));
        }
    }

    private static function user_validator($attributes, $form_type)
    {
        $validator = new Valitron\Validator($attributes);

        if ($form_type == 'register') {
            $validator->rule('required', array('username', 'password',
                'first_name', 'last_name', 'phone_number', 'street_address',
                'postal_code', 'city', ));
            $validator->rule('lengthMax', 50, array('username', 'password'));
            $validator->rule('email', 'email');
        } elseif ($form_type == 'user_info') {
            $validator->rule('required', array('first_name', 'last_name',
                'phone_number', 'street_address', 'postal_code', 'city', ));
            $validator->rule('email', 'email');
        }

        return $validator;
    }
}
