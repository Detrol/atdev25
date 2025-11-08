<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DarkmodeToggle extends Component
{
    /**
     * Storlek på toggle-knappen
     */
    public string $size;

    /**
     * Visa text-label vid sidan av ikonen
     */
    public bool $showLabel;

    /**
     * Extra CSS-klasser
     */
    public string $class;

    /**
     * Skapa en ny komponent-instans
     */
    public function __construct(
        string $size = 'md',
        bool $showLabel = false,
        string $class = ''
    ) {
        $this->size = in_array($size, ['sm', 'md', 'lg']) ? $size : 'md';
        $this->showLabel = $showLabel;
        $this->class = $class;
    }

    /**
     * Hämta storleksklasser baserat på size prop
     */
    public function getSizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'p-1.5 text-sm',
            'md' => 'p-2 text-base',
            'lg' => 'p-3 text-lg',
            default => 'p-2 text-base',
        };
    }

    /**
     * Hämta ikonstorlek baserat på size prop
     */
    public function getIconSize(): string
    {
        return match ($this->size) {
            'sm' => 'w-4 h-4',
            'md' => 'w-5 h-5',
            'lg' => 'w-6 h-6',
            default => 'w-5 h-5',
        };
    }

    /**
     * Rendera komponenten
     */
    public function render(): View
    {
        return view('components.darkmode-toggle');
    }
}
