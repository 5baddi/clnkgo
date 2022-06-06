<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Validation\Rules\In;
use App\Http\Requests\PaginationRequest;

class BookmarkedTweetsRequest extends PaginationRequest
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
                'match'         =>  ['string', new In(['bookmarked'])],
                'category'      =>  ['nullable', 'string', 'min:1'],
            ]
        );
    }
}
