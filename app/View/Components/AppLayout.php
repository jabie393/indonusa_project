<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Whether to hide the sidebar for this layout.
     */
    public bool $hideSidebar = false;

    /**
     * Create the component instance.
     */
    public function __construct($hideSidebar = false)
    {
        $this->hideSidebar = filter_var($hideSidebar, FILTER_VALIDATE_BOOLEAN);
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('admin.layouts.app');
    }
}
