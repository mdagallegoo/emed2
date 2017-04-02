<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Specialization;

class SpecializationComposer
{
    public function __construct()
    {
        
    }

    public function compose(View $view)
    {
        $view->with('specialization', Specialization::with('subspecializations')->get());
    }
}