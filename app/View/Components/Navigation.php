<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Profile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Navigation extends Component
{
    public bool $loggedIn;

    /**
     * Create a new component instance.
     */
    public function __construct(public Profile $profile)
    {
        $this->loggedIn = Auth::check();
        $this->profile = Auth::user()->profile;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navigation');
    }
}
