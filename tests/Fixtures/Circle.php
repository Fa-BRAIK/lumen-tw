<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Tests\Fixtures;

use Illuminate\View\Component;

class Circle extends Component
{
    public function render(): string
    {
        return <<<'blade'
            <div {{ $attributes->twMerge('w-10 h-10 rounded-full bg-red-500') }}></div>
        blade;
    }
}
