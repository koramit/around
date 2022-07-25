<?php

namespace App\Traits;

trait AppLayoutSessionFlashable
{
    protected function setFlash(array $flash): void
    {
        session()->flash('page-title', $flash['page-title']);
        session()->flash('main-menu-links', $flash['main-menu-links']);
        session()->flash('action-menu', $flash['action-menu']);

        foreach (['message', 'hn', 'breadcrumbs', 'navs'] as $key) {
            if (isset($flash[$key])) {
                session()->flash($key, $flash[$key]);
            }
        }
    }
}
