<?php

namespace App\Http\Requests\Requests;

class SendMailRequest extends SendDMRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'email'   => ['required', 'email'],
                'subject' => ['required', 'string', 'min:1']
            ]
        );
    }
}