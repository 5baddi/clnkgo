<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\In;

class AnalyticsRequest extends PaginationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

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
                'start-date'    =>  ['nullable', 'date', 'date_format:Y-m-d'],
                'end-date'      =>  ['nullable', 'date', 'date_format:Y-m-d'],
                'ap'            =>  ['nullable', 'integer'],
                'pp'            =>  ['nullable', 'integer'],
                'term'          =>  ['nullable', 'string', 'min:1'],
                'sort'          =>  ['nullable', 'string', new In(['published_at', '-published_at', 'last24hrs', 'keywordmatch'])],
                'match'         =>  ['nullable', 'string', new In([-1, 'keyword', 'bookmarked', 'answered'])],
                'category'      =>  ['nullable', 'string', 'min:1'],
                'author'        =>  ['nullable', 'integer'],
            ]
        );
    }
}
