<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

class AcuteHemodialysisAction
{
    protected $REGISTRY_ID = 1;

    protected $ACUTE_HD_ORDER_NOTE_TYPE_ID;

    protected $TIMEZONE = 7;

    protected $PATIENT_TYPES = ['Acute', 'Chronic'];

    public function __construct()
    {
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = config('notes.acute_hd_order');
    }

    protected function setFlash(array $flash)
    {
        session()->flash('page-title', $flash['page-title']);
        session()->flash('main-menu-links', $flash['main-menu-links']);
        session()->flash('action-menu', $flash['action-menu']);
        if ($flash['messages'] ?? null) {
            session()->flash('messages', $flash['messages']);
        }
    }
}
