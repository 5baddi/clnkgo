<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Requests;

use BADDIServices\SourceeApp\Rules\ValidateHCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class ConnectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store'                  =>  ['required', 'string'],
            'h-captcha-response'     =>  [new ValidateHCaptcha()],
        ];
    }
}
