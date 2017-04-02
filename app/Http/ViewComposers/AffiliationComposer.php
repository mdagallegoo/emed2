<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Affiliations;

class AffiliationComposer
{
    public function __construct()
    {
        
    }

    public function compose(View $view)
    {
        $view->with('affiliations', Affiliations::orderBy('affiliations')->get());
    }
}