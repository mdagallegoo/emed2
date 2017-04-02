<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('components.form.specialization-dropdown', 'App\Http\ViewComposers\SpecializationComposer');
        View::composer('components.form.affiliations-dropdown', 'App\Http\ViewComposers\AffiliationComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}