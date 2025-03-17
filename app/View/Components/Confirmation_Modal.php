<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Confirmation_Modal extends Component
{
    /**
     * Create a new component instance.
     */
    public $title = '';
    public $titleColor = '';
    public $message = '';
    public $buttonId = '';
    public $buttonText = '';
    public $route = '';
    public function __construct( $title, $titleColor, $message, $buttonId, $buttonText,$route)
    {
        $this->title = $title;
        $this->titleColor = $titleColor;
        $this->message = $message;
        $this->buttonId = $buttonId;
        $this->buttonText = $buttonText;
        $this->route = $route;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.confirmation_modal');
    }
}
