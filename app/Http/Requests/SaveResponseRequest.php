<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Requests;

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\SavedResponse;
use Illuminate\Foundation\Http\FormRequest;

class SaveResponseRequest extends FormRequest
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
            SavedResponse::TITLE_COLUMN     => ['required', 'string', 'min:1'],
            SavedResponse::CONTENT_COLUMN   => ['nullable', 'string', 'max:' . App::TWEET_CHARACTERS_LIMIT ],
        ];
    }
}