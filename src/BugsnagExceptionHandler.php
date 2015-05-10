<?php namespace HolidayPirates\BugsnagLumen;

use Exception;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class BugsnagExceptionHandler extends ExceptionHandler {
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        foreach ($this->dontReport as $type)
        {
            if ($e instanceof $type)
                parent::report($e);
        }

        $bugsnag = app('bugsnag');

        if ($bugsnag) {
            $bugsnag->notifyException($e, null, "error");
        }
        parent::report($e);
    }
}
