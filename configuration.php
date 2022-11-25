<?php

use Icinga\Application\Config;
use Icinga\Authentication\Auth;

$auth = Auth::getInstance();

$section = $this->menuSection(N_('CA'));

if ($auth->hasPermission('ca/overview')){
   $section->add(N_('Certificate Authority'), ['url' => 'ca']);
}

$this->providePermission(
    'ca/oper',
    $this->translate('Certificate Authority Operator')
);

$this->provideConfigTab('config', array(
    'title' => 'Configuration',
    'label' => 'Configuration',
    'url' => 'config'
));
