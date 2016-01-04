<?php

/**
 * Handles list, add and edit operations related to orders.
 */
class OrdersController extends BaseController
{
    /**
     * Retrieves all orders (if the authenticated user is an admin) or orders 
     * of a certain customer (if the authenticated user is a customer) from the 
     * database as well as their products. Displays the order list.
     */
    public static function get_orders()
    {
        $orders = array();

        $user = self::get_user_logged_in();

        if ($user) {
            if ($user->username == 'admin') {
                $orders = Orders::findAll();
            } else {
                $orders = Orders::findByUser($user->id);
            }
        }

        foreach ($orders as $order) {
            $order_products = OrderProduct::findByOrder($order->id);

            foreach ($order_products as $index => $order_product) {
                $order->products[$index] =
                    json_decode($order_product->product_info);
            }

            $order->user_info = json_decode($order->user_info);
        }

        View::make('order/list.html', array('orders' => $orders));
    }

    /**
     * Displays the form for making a new order.
     */
    public static function add_form()
    {
        $user = self::get_user_logged_in();

        View::make('order/add.html', array('user' => $user));
    }

    /**
     * Validates data submitted in the add form. If the validation fails,
     * displays the add page. Otherwise, creates a new order, saves it and
     * its products to the database and clears the 'cart' session variable. 
     * Redirects to the front page.
     */
    public static function add()
    {
        $params = $_POST;

        date_default_timezone_set('Europe/Helsinki');

        $attributes = array(
            'user_id' => $params['user_id'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'phone_number' => $params['phone_number'],
            'street_address' => $params['street_address'],
            'postal_code' => $params['postal_code'],
            'city' => $params['city'],
            'delivery_method' => $params['delivery_method'],
        );

        $validator = self::order_validator($attributes);

        if ($validator->validate()) {
            $order = new Orders(array(
                'user_id' => $attributes['user_id'],
                'order_time' => date('d-m-Y H:i:s'),
                'actual_delivery_time' => null,
                'delivery_address' => $attributes['street_address'].', '.
                                      $attributes['postal_code'].' '.
                                      $attributes['city'],
                'delivery_method' => $attributes['delivery_method'],
                'total_price' => $_SESSION['cart']['price'],
                'status' => 'Jonossa',
            ));

            if (!$params['user_id']) {
                $user = array('user_id' => null, 'first_name' => $params['first_name'], 'last_name' => $params['last_name'],
                    'phone_number' => $params['phone_number'], );

                $order->user_id = null;

                $order->user_info = json_encode($user);
            } else {
                $order->user_info = json_encode(Users::findOne($_SESSION['user']));
            }

            if ($order->delivery_method == 'Nouto') {
                $order->agreed_delivery_time = date('d-m-Y H:i:s',
                                        strtotime('+15 minutes',
                                        strtotime($order->order_time)));
            } else {
                $order->agreed_delivery_time = date('d-m-Y H:i:s',
                                        strtotime('+60 minutes',
                                        strtotime($order->order_time)));
            }

            $order_id = $order->save();

            self::set_order_products($order_id, $_SESSION['cart']['items']);

            $_SESSION['cart'] = null;

            Redirect::to('/',
                   array('message' => 'Tilauksesi on lähetetty, kiitos!'));
        } else {
            View::make('order/add.html', array('errors' => $validator->errors(),
                'user' => $attributes, ));
        }
    }

    /**
     * Retrieves an order from the database and displays the form for 
     * editing it.
     * 
     * @param int $id id of the order to edit 
     */
    public static function edit_form($id)
    {
        $order = Orders::findOne($id);

        View::make('order/edit.html', array('order' => $order));
    }

    /**
     * Updates the status of an order and redirects back to the order history 
     * page.
     *  
     * @param int $id id of the edited order
     */
    public static function edit($id)
    {
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'status' => $params['status'],
        );

        if ($attributes['status'] == 'Toimitettu') {
            $attributes['actual_delivery_time'] = date('d-m-Y H:i:s');
        }

        $order = new Orders($attributes);

        $order->update();

        Redirect::to('/tilaushistoria', array(
            'message' => 'Tilaustietoja muokattu!', ));
    }

    private static function set_order_products($order_id, $products)
    {
        foreach ($products as $product) {
            $product = json_decode($product);

            $order_product = new OrderProduct(array(
                'order_id' => $order_id,
                'product_id' => $product->id,
                'product_info' => json_encode($product),
            ));

            $order_product->save();
        }
    }

    private static function order_validator($attributes)
    {
        $validator = new Valitron\Validator($attributes);

        $validator->rule('required',
                array('first_name', 'last_name', 'phone_number',
                      'street_address', 'postal_code',
                      'city', 'delivery_method', ));

        return $validator;
    }
}
