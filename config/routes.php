<?php

function check_logged_in(){
  BaseController::check_logged_in();
}

function check_if_admin(){
  BaseController::check_if_admin();
}

$routes->get('/', function() {
  DefaultController::index();
}); 

$routes->get('/info', function() {
  DefaultController::info();
});

$routes->post('/ostoskori/:id/lisaa', function($id) {
  CartController::add($id);
});

$routes->post('/ostoskori/:id/poista', function($id) {
  CartController::delete($id);
});

$routes->post('/ostoskori/tyhjenna', function() {
  CartController::deleteAll();
});

$routes->get('/kassa', function() {
  OrdersController::add_form();
});

$routes->post('/kassa', function() {
  OrdersController::add();
});

$routes->get('/tilaukset/:id/muokkaa', 'check_logged_in', function($id) {
  OrdersController::edit_form($id);
});

$routes->post('/tilaukset/:id/muokkaa', 'check_logged_in', 'check_if_admin', 
              function($id) {
  OrdersController::edit($id);
});

$routes->get('/tilaushistoria', 'check_logged_in', function() {
  OrdersController::get_orders();
});

$routes->get('/kirjaudu', function() {
  UsersController::login_form(); 
});

$routes->post('/kirjaudu', function() {
  UsersController::login(); 
});

$routes->get('/rekisteroidy', function() {
  UsersController::add_form();
});

$routes->post('/rekisteroidy', function() {
  UsersController::add();
});

$routes->post('/kirjaudu_ulos', 'check_logged_in', function() {
  UsersController::logout();
});

$routes->get('/omat_tiedot', 'check_logged_in', function() {
  UsersController::edit_form();
}); 

$routes->post('/omat_tiedot', 'check_logged_in', function() {
  UsersController::edit();
});

$routes->get('/tuotteet/kebabit', function() {
  ProductController::get_products('Kebab');
});

$routes->get('/tuotteet/juomat', function() {
  ProductController::get_products('Juoma');
});

$routes->get('/tuotteet/pizzat', function() {
  ProductController::get_products('Pizza');
});

$routes->get('/tuotteet/salaatit', function() {
  ProductController::get_products('Salaatti');
});

$routes->get('/tuotteet/:category/lisaa', 'check_logged_in', 'check_if_admin', 
             function($category) {
  ProductController::add_form($category);
});

$routes->post('/tuotteet/:category/lisaa', 'check_logged_in', 'check_if_admin', 
              function() {
  ProductController::add();
});

$routes->get('/tuotteet/:category/:id/muokkaa', 'check_logged_in', 
             'check_if_admin', function($category, $id) {
  ProductController::edit_form($id);
});

$routes->post('/tuotteet/:category/:id/muokkaa', 'check_logged_in', 
              'check_if_admin', function($category, $id) {
  ProductController::edit($id);
});

$routes->post('/tuotteet/:id/poista', 'check_logged_in', 'check_if_admin', 
              function($id) {
  ProductController::delete($id);
});  

$routes->get('/ainesosat', 'check_logged_in', 'check_if_admin', function() {
  IngredientController::get_ingredients();
});  

$routes->get('/ainesosat/:id/muokkaa', 'check_logged_in', 'check_if_admin', 
             function($id){
  IngredientController::edit_form($id);
});

$routes->post('/ainesosat/:id/muokkaa', 'check_logged_in', 'check_if_admin', 
              function($id){
  IngredientController::edit($id);
});

$routes->get('/ainesosat/lisaa', 'check_logged_in', 'check_if_admin', 
             function(){
  IngredientController::add_form();
});

$routes->post('/ainesosat/lisaa', 'check_logged_in', 'check_if_admin', 
              function(){
  IngredientController::add();
});

$routes->post('/ainesosat/:id/poista', 'check_logged_in', 'check_if_admin', 
              function($id){
  IngredientController::delete($id);
});  

  