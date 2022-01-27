<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Mail\ExceptionOccured;

/**
 * This is your application's exception handler
 *
 * Class Handler
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            // $this->sendEmail($exception); // sends an email
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // CUSTOM EXCEPTION HANDLER
        if ($exception instanceof MethodNotAllowedHttpException){
            $response = setResponse('METHOD_NOT_ALLOWED');
            return send_json_response($request, $response);
        }
        return parent::render($request, $exception);
    }

    public function sendEmail(Throwable $exception)
    {
       try {
            $email = config('CONSTANT.SEND_ERROR_REPORT_TO');
            $html = ExceptionHandler::convertExceptionToResponse($exception)->getContent();
            $url = (!empty(\Request::fullUrl())) ? \Request::fullUrl() : "From console";
            \Mail::to($email)->send(new ExceptionOccured($html, 'Exception url : ' . $url));
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}
