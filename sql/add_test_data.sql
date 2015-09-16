INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Hawaii', '0', 'Pizza', FALSE, 'Tämä on kuvaus.');

INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Opera', '0', 'Pizza', FALSE, 'Tämä on kuvaus.');

INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Riisikebab', '0', 'Kebab', FALSE, 'Tämä on kuvaus.');

INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Kanasalaatti', '0', 'Salaatti', FALSE, 'Tämä on kuvaus.');

INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Coca-Cola 0.5 l', '2.0', 'Juoma', FALSE, 'Tämä on kuvaus.');

INSERT INTO Product (product_name, price, category, customizable, description) VALUES 
            ('Fantasiapizza', '0', 'Pizza', TRUE, 'Tämä on kuvaus.');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kinkku', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Ananas', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tonnikala', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Poro', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Banaani', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Paprika', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kebab-liha', 'Täyte', '3.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Riisi', 'Kebab-lisuke', '3.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Ranskalaiset', 'Kebab-lisuke', '3.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Salaatti', 'Täyte', '0.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kurkku', 'Täyte', '0.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tomaatti', 'Täyte', '0.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kana', 'Täyte', '2.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kebab-kastike', 'Kastike', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Krutonki', 'Täyte', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Gluteeniton pohja', 'Pizzapohja', '5.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tavallinen pohja', 'Pizzapohja', '5.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Kuitupohja', 'Pizzapohja', '6.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Puolijuusto', 'Juusto', '0.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tavallinen juusto', 'Juusto', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tuplajuusto', 'Juusto', '1.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Tomaattikastike', 'Kastike', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('BBQ-kastike', 'Kastike', '1.5');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Jogurttikastike', 'Kastike', '1.0');

INSERT INTO Ingredient (ingredient_name, ingredient_type, price) 
            VALUES ('Salaattikastike', 'Kastike', '1.0');

INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('1', '1');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('1', '2');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('2', '3');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('2', '1');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '7');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '8');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '10');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '11');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '12');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('3', '14');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '10');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '11');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '12');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '13');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '15');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('4', '25');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('1', '17');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('1', '22');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('2', '17');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('2', '22');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('1', '20');
INSERT INTO ProductIngredient (product_id, ingredient_id) VALUES ('2', '20');