<?php

namespace App\Traits;

trait AppLayoutSessionFlashable
{
    protected function setFlash(array $flash): void
    {
        session()->flash('page-title', $flash['page-title']);
        session()->flash('main-menu-links', $flash['main-menu-links']);
        session()->flash('action-menu', $flash['action-menu']);
        if ($flash['messages'] ?? null) {
            session()->flash('messages', $flash['messages']);
        }
    }
}
