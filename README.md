Bugsnag Notifier for Lumen 
==========================

The Bugsnag Notifier for Lumen gives you instant notification of errors and
exceptions in your Lumen PHP applications.
(based on package for Laravel https://github.com/holidaypirates/bugsnag-lumen) 

[Bugsnag](https://bugsnag.com) captures errors in real-time from your web, 
mobile and desktop applications, helping you to understand and resolve them 
as fast as possible. [Create a free account](https://bugsnag.com) to start 
capturing errors from your applications.


How to Install
--------------

1.  Install the `holidaypirates/bugsnag-lumen` package

    ```shell
    $ composer require "holidaypirates/bugsnag-lumen:1.*"
    ```

2.  Update `bootstrap/app.php` to activate Bugsnag

    ```php
    # Add `BugsnagLumenServiceProvider` to the `bootstrap/app.php`
    $app->register('HolidayPirates\BugsnagLumen\BugsnagLumenServiceProvider')
    ```

3. Use the Bugsnag exception handler from `App/Exceptions/Handler.php`.

    ```php
    # DELETE this line
    use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
    ```

    ```php
    # ADD this line instead
    use HolidayPirates\BugsnagLumen\BugsnagExceptionHandler as ExceptionHandler
    ```

    After this change, your file should look like this:

    ```php
    <?php namespace App\Exceptions;

    use Exception;
    use HolidayPirates\BugsnagLumen\BugsnagExceptionHandler as ExceptionHandler;

    class Handler extends ExceptionHandler {
        ...
    }

    ```

Configuration
-------------------------

1. Create a file `config/bugsnag.php` that contains your API key:

2. Configure your `api_key`:

    ```php
    <?php # config/bugsnag.php

    return array(
        'api_key' => 'YOUR-API-KEY-HERE'
    );
    ```

3.  Optionally, you can add the `notify_release_stages` key to the same file
    above to define which Laravel environments will send Exceptions to Bugsnag.

    ```php
    return array(
        'api_key' => 'YOUR-API-KEY-HERE',
        'notify_release_stages' => ['production', 'staging']
    );
    ```

Sending Custom Data With Exceptions
-----------------------------------

It is often useful to send additional meta-data about your app, such as 
information about the currently logged in user, along with any
error or exceptions, to help debug problems. 

To send custom data, you should define a *before-notify* function, 
adding an array of "tabs" of custom data to the $metaData parameter. For example:

```php
app('bugsnag')->setBeforeNotifyFunction("before_bugsnag_notify");

function before_bugsnag_notify($error) {
    // Do any custom error handling here

    // Also add some meta data to each error
    $error->setMetaData(array(
        "user" => array(
            "name" => "James",
            "email" => "james@example.com"
        )
    ));
}
```

See the [setBeforeNotifyFunction](https://bugsnag.com/docs/notifiers/php#setbeforenotifyfunction)
documentation on the `bugsnag-php` library for more information.


Sending Custom Errors or Non-Fatal Exceptions
---------------------------------------------

You can easily tell Bugsnag about non-fatal or caught exceptions by 
calling `app('bugsnag')->notifyException`:

```php
app('bugsnag')->notifyException(new Exception("Something bad happened"));
```

You can also send custom errors to Bugsnag with `Bugsnag::notifyError`:

```php
app('bugsnag')->notifyError("ErrorType", "Something bad happened here too");
```

Both of these functions can also be passed an optional `$metaData` parameter,
which should take the following format:

```php
$metaData =  array(
    "user" => array(
        "name" => "James",
        "email" => "james@example.com"
    )
);
```


Error Reporting Levels
----------------------

By default we'll use the value of `error_reporting` from your `php.ini`
or any value you set at runtime using the `error_reporting(...)` function.

If you'd like to send different levels of errors to Bugsnag, you can call
`setErrorReportingLevel`, for example:

```php
app('bugsnag')->setErrorReportingLevel(E_ALL & ~E_NOTICE);
```


Additional Configuration
------------------------

The [Bugsnag PHP Client](https://bugsnag.com/docs/notifiers/php)
is available as `Bugsnag`, which allows you to set various
configuration options, for example:

```php
app('bugsnag')->setReleaseStage("production");
```

See the [Bugsnag Notifier for PHP documentation](https://bugsnag.com/docs/notifiers/php#additional-configuration)
for full configuration details.


Reporting Bugs or Feature Requests
----------------------------------

Please report any bugs or feature requests on the github issues page for this
project here:

<https://github.com/holidaypirates/bugsnag-lumen/issues>
