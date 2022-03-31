<?php


namespace App\Http\Requests;

use Illuminate\Http\Request;

/**
 * @author Tashya Dwi Askara Siahaan
 **/
interface FormRequest
{
    /**
     * @return Request
     */
    public function getParams(): Request;
}
