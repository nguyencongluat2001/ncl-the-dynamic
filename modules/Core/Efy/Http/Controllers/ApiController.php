<?php

namespace Modules\Core\Efy\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\Core\Efy\Http\Debug\ApiDebug;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Lang;

/**
 * Base ApiController.
 * 
 * @author Toanph <skype: toanph1505>
 */
class ApiController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * status code.
     *
     * @var int
     */
    protected $statusCode = 200;

    private $bodyResponse;

    private $addition;

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode = 200)
    {
        $this->statusCode = $statusCode;
    }


    /**
     * response.
     *
     * @param array $data
     * @param array $status
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($bodyResponse = [], $addition = [])
    {
        $this->bodyResponse = $bodyResponse;
        $this->addition = $addition;
        return $this;
    }

    private function getMessage($message, $success)
    {
        if ($message == "") {
            $action = Route::getCurrentRoute()->getActionMethod();
            if ($success) {
                $key = "Api.success_" . $action;
            } else {
                $key = "Api.error_" . $action;
            }
            if (Lang::has($key)) {
                $message = Lang::get($key);
            }
        }
        return $message;
    }

    protected function success($status = 200, $message = "", $headers = [])
    {
        $body['status'] = true;
        $body['message'] = $this->getMessage($message, true);
        $body['data'] = $this->bodyResponse;
        if ($this->addition) {
            $body = array_merge($body, $this->addition);
        }
        if (ApiDebug::check()) {
            $body['db_log'] = $this->getLogSql();
        }
        return response()->json($body, $status, $headers);
    }

    protected function error($status = 401, $message = "", $headers = [])
    {
        $body['status'] = false;
        $body['message'] = $this->getMessage($message, false);
        $body['data'] =  $this->bodyResponse;
        if (ApiDebug::check()) {
            $body['db_log'] = $this->getLogSql();
        }
        return response()->json($body, $status, $headers);
    }

    private function getLogSql()
    {
        $dbSytem = ApiDebug::getDbName("sqlsrv");
        $sqlLog = session('sqlLog');
        $body[$dbSytem] = ApiDebug::getDbLog("sqlsrv");
        if ($sqlLog) {
            $body['other'] = $sqlLog;
        }
        return $body;
    }
}
