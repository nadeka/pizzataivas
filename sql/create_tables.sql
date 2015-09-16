CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(125) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50),
    phone_number VARCHAR(50) NOT NULL,
    street_address VARCHAR(50),
    postal_code VARCHAR(50),
    city VARCHAR(50)
);

CREATE TABLE Orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES Users(id) ON DELETE SET NULL,
    order_time VARCHAR(100) NOT NULL,
    agreed_delivery_time VARCHAR(100) NOT NULL,
    actual_delivery_time VARCHAR(100),
    delivery_address VARCHAR(200),
    delivery_method VARCHAR(50) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(100) NOT NULL,
    user_info VARCHAR
);

CREATE TABLE Product (
    id SERIAL PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2),
    category VARCHAR(50) NOT NULL,
    customizable BOOLEAN DEFAULT FALSE NOT NULL,
    description VARCHAR(1000)
);

CREATE TABLE OrderProduct (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES Orders(id),
    product_id INTEGER REFERENCES Product(id) ON DELETE SET NULL,
    product_info VARCHAR
);

CREATE TABLE Ingredient (
    id SERIAL PRIMARY KEY,
    ingredient_name VARCHAR(50) NOT NULL,
    ingredient_type VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE ProductIngredient (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES Product(id) ON DELETE CASCADE NOT NULL,
    ingredient_id INTEGER REFERENCES Ingredient(id) ON DELETE CASCADE NOT NULL
);

