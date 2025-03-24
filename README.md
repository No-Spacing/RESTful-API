# RESTful-API
 VueJS, VuetifyJS and Laravel CRUD RESTful API
 <br>You can use tools like Postman or cURL to test your API endpoints by sending various HTTP requests (GET, POST, PUT, DELETE) to interact with the data.
 * GET request: http://localhost:8000/api/data
 * POST request: http://localhost:8000/api/create with a JSON payload
 * PUT request: http://localhost:8000/api/update with updated JSON payload
 * DELETE request: http://localhost:8000/api/delete/{id}
## Installation
 1. open command prompt inside the app project folder and type command `composer update`.
 2. rename the `.env.example` file to `.env`.
 3. inside the command prompt type `php artisan key:generate`.
 4. type `php artisan migrate` if the database does not exist it will prompt then type yes to create the database.
 5. type npm install
 6. then type `php artisan serve` and open another command prompt to the same folder and type `npm run dev` to run the VueJS.
