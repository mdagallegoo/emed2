<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.text', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsRadio', 'components.form.radio', ['name', 'label' => null, 'options', 'selected' => null]);
        Form::component('bsSpecializationDropdown', 'components.form.specialization-dropdown', ['name', 'label' => null, 'selected' => null]);
        Form::component('bsAffiliationDropdown', 'components.form.affiliations-dropdown', ['name', 'label' => null, 'selected' => null]);


        // Validator::extend('current_password_match', function($attribute, $value, $parameters, $validator) {
        //     return Hash::check($value, Auth::user()->password);
        // });
        Validator::extend('current_password_match', 'App\Validators\PasswordMatch@check');

        Form::component('bsDate', 'components.form.date', ['name', 'label' => null, 'value' => null, 'attributes' => []]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
