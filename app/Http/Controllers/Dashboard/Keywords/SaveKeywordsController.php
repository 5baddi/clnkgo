<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Http\Controllers\Dashboard\Keywords;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\KeywordsRequest;
use App\Models\User;
use BADDIServices\ClnkGO\Entities\Alert;
use Illuminate\Validation\ValidationException;
use BADDIServices\ClnkGO\Http\Controllers\DashboardController;

class SaveKeywordsController extends DashboardController
{
    public function __invoke(KeywordsRequest $request)
    {
        try {
            DB::beginTransaction();

            $keywords = trim(strtolower($request->input(User::KEYWORDS_COLUMN)));
            $this->userService->update($this->user, [User::KEYWORDS_COLUMN => $keywords]);

            DB::commit();

            return redirect()->route('dashboard.keywords')
                ->with(
                    'alert', 
                    new Alert('Your keywords has been saved successfully', 'success')
                );
        } catch (ValidationException $ex){
            return redirect()->route('dashboard.keywords')
                ->withErrors($ex->errors)
                ->withInput();
        } catch (Throwable $ex){
            DB::rollBack();

            return redirect()->route('dashboard.keywords')
                ->with(
                    'alert', 
                    new Alert('An error occurred while saving your keywords!')
                )
                ->withInput();
        }
    }
}