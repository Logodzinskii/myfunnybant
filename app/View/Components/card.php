<?php

namespace App\View\Components;

use Illuminate\View\Component;

class card extends Component
{
    public $key, $header, $img, $ozonid;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($key, $header, $img, $ozonid)
    {
        $this->key = $key;
        $this->header = $header;
        $this->img = $img;
        $this->ozonid = $ozonid;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
