<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Http\Controllers\Admin\Pages;

use Throwable;
use BADDIServices\SafeHTML\SafeHTML;
use BADDIServices\SourceeApp\AppLogger;
use BADDIServices\SourceeApp\Entities\Alert;
use BADDIServices\SourceeApp\Services\PageService;
use BADDIServices\SourceeApp\Http\Requests\Admin\Pages\StorePageRequest;
use BADDIServices\SourceeApp\Http\Controllers\AdminController as ControllersAdminController;
use BADDIServices\SourceeApp\Models\Page;

class StorePageController extends ControllersAdminController
{
    public function __construct(private PageService $pageService, private SafeHTML $safeHTML) {}

    public function __invoke(StorePageRequest $request)
    {
        try {
            $attributes = $request->validated();

            $attributes[Page::CONTENT_COLUMN] = $this->safeHTML->sanitizeHTML($request->input(Page::CONTENT_COLUMN));

            // TODO: upload images https://www.codewall.co.uk/install-summernote-with-laravel-tutorial/
            
            $this->pageService->create($attributes);

            return redirect()
                ->route('admin.pages')
                ->with(
                    'alert', 
                    new Alert('Page has been created successfully', 'success')
                );
        } catch (Throwable $e) {
            AppLogger::error($e, 'admin:create-page');

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'alert', 
                    new Alert('An occurred error while saving the new page')
                );
        }
    }
}