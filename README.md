# Stock analyzer
Assess a list of stocks and calculate its maximum profit(or)minimum loss.


## Input file
The input file should be in CSV format.
eg:
```
id_no,date,stock_name,price
1,11-02-2022,AAPL,320.12
2,11-02-2022,GOOGL,1510
3,11-02-2022,MSFT,185
4,12-02-2022,GOOGL,1518
```

Supported date formats for the input file is:
* `11-02-2020`
* `2020-02-11`
* `11 Feb 2020`
* `2020/02/12`


## Testing
* Clone the repository.
* Execute `composer install`.
* Use `composer test` to run the tests.


## Deployment
* Clone the repository
* The webroot should point to the `public` directory.
* Execute the following command in the project directory to install the HTTP router.
```
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
```


## Pending tasks
* Refactor the code for view generation into a function.
