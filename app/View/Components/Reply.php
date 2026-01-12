<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Reply extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Post $post, public bool $showEngagement = true, public bool $showReplies = false)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reply');
    }
}
