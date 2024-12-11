<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Spinner extends Component
{
    public function __construct(
        public string $class = '',
        public string $containerClass = ''
    ) {}

    public function render()
    {
        return view('components.spinner');
    }
}
