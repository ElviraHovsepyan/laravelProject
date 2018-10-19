##haymatakara.am


Controllers:

AuthController.php
path: App\Http\Controllers\Api\AuthController
Provides user registration and login via Api.

User registration - url: "http://laravelproject.loc/api/register"
Parameters: 'first_name','last_name','email','password','password_confirmation'
Returns code 1005 if validation fails, with validation error;
Returns code 1006 with message to verify email if registration is successful.

User login - url: "http://laravelproject.loc/api/login"
Parameters: 'email', 'password'
Returns code 1001 if email is invalid;
Returns code 1002 if password does not match;
Returns code 1003 if email is not verified;
Returns code 1007 and the api_token if login is successful.
Each code has it's own message.



ApiController.php
path: App\Http\Controllers\Api\ApiController
Includes static variables and functions used for Api requests.



ProductsController.php
path: App\Http\Controllers\Api\ProductsController
Provides products handling via API.

View all user products - url: "http://laravelproject.loc/api/products"
Parameters: 'token' in headers.
Returns code 1004 if token is incorrect.
Returns the list of all user's products in case of success.
If user wants to see the first 5 products, he can pass the optional parameter /api/products/5;
If user wants to see 5 products from 3-rd product, he can pass the second optional parameter /api/products/5/3;

View one product - url: "http://laravelproject.loc/api/show/{id}"
Parameters: 'token' in headers.
Returns code 1004 if token is incorrect.
Returns code 2008 if id is incorrect;
Returns product info in case of success.

Add a new product - url: "http://laravelproject.loc/api/store"
Parameters: 'token' in headers; 'name','price','description','code','quantity','unit','image'.
Returns code 2005 if validation fails:  fields name, code, price are required;
Returns code 1004 if token is incorrect.
Returns code 2007 in case of success.

Edit a product - url: "http://laravelproject.loc/api/update/{id}"
Parameters: 'token' in headers, 'name','price','description','code','quantity','unit','image'
Returns code 2005 if validation fails:  fields name, code, price are required;
Returns code 1004 if token is incorrect.
Returns code 2007 in case of success.

Delete a product - url: "http://laravelproject.loc/api/destroy/{id}"
Parameters: 'token' in headers
Returns code 1004 if token is incorrect;
Returns code 2008 if id is incorrect;
Returns code 2009 in case of success.