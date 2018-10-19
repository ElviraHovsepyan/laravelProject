##haymatakara.am

Controllers:


RegisterController.php
path: App\Http\Controllers\RegisterController
Provides user registration and login.


VerifyController.php
path: App\Http\Controllers\VerifyController
Provides user email-verification after successful registration.
url: "http://laravelproject.loc/verify/{verification_code}"


ProductsController.php
path: App\Http\Controllers\ProductController
Provides Product handling : view, add, edit, delete products.



Models:


User.php
path: App\User
Includes static functions for registration and login used both for Api and website.
Provides relations with tables products,categories,roles.

Product.php
path: App\Product
Includes static functions for product handling used both for Api and website.
Provides relations with tables users,categories,stock.

Category.php
path: App\Category
Provides relations with tables users,products.

Role.php
path: App\Role
Provides relations with table users.

Stock.php
path: App\Stock
Provides relations with table products.

Product_Category.php
path: App\Product_Category
Provides relations between tables products and categories.

User_Category.php
path: App\User_Category
Provides relations between tables categories and users.

User_Product.php
path: App\User_Product
Provides relations between tables products and users.
