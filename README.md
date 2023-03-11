## Assignment

## Task
This is a [Laravel][laravel] project which implements an example of a personal wishlist
where users can add their wanted products with some basic information (price, link etc.) and
view the list.

#### Refactoring
The `ProductController` needs to be refactored to be less messy. Please use your best judgement to improve the code. Your task
is to identify the imperfect areas and improve them, but you have to make sure that any change won't affect backward compatability.

#### New feature
Please modify the project to add statistics for the wishlist products. Statistics should include:

- The total count of products
- The total count of products per each website.
- The average price of a product
- The website that has the highest total price of its products.
- The total price of products added this month.

The statistics should be exposed using an API endpoint. **Moreover**, the user should be able to
display the statistics using a CLI command.

Please also include a way for the command to display only one piece of information from the statistics,
for example just the average price. You can add a command parameter/option to specify which
one of the statistics should be displayed.

## Questions
Please write your answers to following questions.

> **Please in a brief, explain your implementation of the new feature**
>Added a migration file to create a column in products used to call api(s) easier and more efficient
>Added user sanctum to secure api(s) if needed
>Added a way for wishlists CRUD
> _..._

> **For the refactoring, would you change something else if you had more time?**
>After adding the column([DOMAIN]), Modified the ProductController and written comments
> _..._

## Running the project
This project requires a database to run. For the server part, you can use `php artisan serve`
or whatever you're most comfortable with.

You can use the Database seeded attached with the project to get data to work with.

#### Running tests
You can run the attached project tests using `php artisan test` command.

[laravel]: https://laravel.com/docs/9.x




#### My custom part of explanation
>The git repo. used is "https://github.com/xxshagrathxx/oranaa"
>General comments found within the project discribing what the lines of code are used for
>The README.md file contatins some extra explanation
>Created a file ([run_api.sh]) at the root directory to run Api(s) as requested