<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Header extends Component
{
    public array $menuItems = [
        'home'
    ];

    private array $clientMenuItems = [
        'account',
        'logout'
    ];

    private array $guestMenuItems = [
        'login',
        'register'
    ];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menuItems = array_merge(
            $this->menuItems,
            Auth::check() ?
                $this->clientMenuItems :
                $this->guestMenuItems
        );
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header');
    }
}
