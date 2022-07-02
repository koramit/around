<?php

namespace App\Traits;

trait AppLayoutSessionFlashable
{
    protected function setFlash(array $flash): void
    {
        session()->flash('page-title', $flash['page-title']);
        session()->flash('main-menu-links', $flash['main-menu-links']);
        session()->flash('action-menu', $flash['action-menu']);
        if (isset($flash['messages'])) {
            session()->flash('messages', $flash['messages']);
        }
        if (isset($flash['hn'])) {
            session()->flash('hn', $flash['hn']);
        }
        if (isset($flash['breadcrumbs'])) {
            session()->flash('breadcrumbs', $flash['breadcrumbs']);
        }
    }
}
