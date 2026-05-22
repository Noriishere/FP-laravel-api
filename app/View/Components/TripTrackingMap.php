<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TripTrackingMap extends Component
{
    public $scheduleId;

    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

    public function render(): View|Closure|string
    {
        return view(
            'components.trip-tracking-map'
        );
    }
}