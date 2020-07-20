# Askync/Utils
###Some Api Utilities for laravel/lumen framework

Success Response  
```php
return \Askync\Utils\Facades\AskyncResponse::success([
    'name' => 'John Doe',
    'email' => 'doe@john.com'
]);
```

Error Response  
```php
return \Askync\Utils\Facades\AskyncResponse::success(401, 'Unauthorized');
```

Throw Error as Response, Break the process and return response from anywhere in your code
```php
    throw new \Askync\Utils\Utils\ResponseException('Server cannot accept the data type');
```


##Setup
bootstrap/app.php
```php
    ...
    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        \Askync\Utils\Handler\LumenErrorHandler::class
    );

    ...

    $app->register(\Askync\Utils\UtilsServiceProvider::class);
    ...
```
