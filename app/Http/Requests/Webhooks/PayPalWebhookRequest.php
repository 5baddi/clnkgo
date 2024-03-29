<?php

namespace App\Http\Requests\Webhooks;

use BADDIServices\ClnkGO\Domains\PayPalService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class PayPalWebhookRequest extends FormRequest
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
            'event_type'    => ['required', 'string', new In(PayPalService::EVENTS)],
            'id'            => ['required', 'string']
        ];
    }
}