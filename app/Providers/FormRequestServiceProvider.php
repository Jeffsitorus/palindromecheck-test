<?php

namespace App\Providers;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->resolving(FormRequest::class, function ($request, $app) {
            $this->initializeRequest($request, $app['request']);
        });

        $this->app->afterResolving(FormRequest::class, function ($form) {
            $form->validate();
            $form->resolveUser();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }

    protected function initializeRequest(FormRequest $form, Request $current)
    {
        $files = $current->files->all();
        $files = (is_array($files) && count($files) > 0) ? array_filter($files) : $files;
        $form->initialize($current->query->all(), $current->request->all(), $current->attributes->all(), $current->cookies->all(), $files, $current->server->all(), $current->getContent());
        $form->setJson($current->json());
    }
}