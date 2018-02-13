<?php

use Icinga\Application\Config;
use Icinga\Authentication\Auth;

$auth = Auth::getInstance();
if ($auth->hasPermission('ca/overview')){
   $this->menuSection('System')
     ->add('Certificate Authority')
     ->setUrl('ca');
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
