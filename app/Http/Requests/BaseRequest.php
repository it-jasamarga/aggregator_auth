<?php


namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * @author Tashya Dwi Askara Siahaan
 **/
class BaseRequest extends Controller implements FormRequest
{

    protected $params;
    private $request;

    /**
     * BaseRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params = $request->all();
        $this->request = $request;
    }

    public function getParams(): Request
    {
        return $this->request->replace($this->params);
    }
}
