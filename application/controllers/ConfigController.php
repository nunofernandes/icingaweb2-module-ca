<?php

namespace Icinga\Module\Ca\Controllers;

use Icinga\Web\Controller;
use Icinga\Application\Config;
use Icinga\Module\Ca\Forms\Config\CaConfigForm;

class ConfigController  extends Controller
{
    public function init()
    {
	$this->assertPermission('config/modules');
	parent::init();
    }

    public function indexAction()
    {
	$this->view->tabs = $this->Module()->getConfigTabs()->activate('config');
	$this->view->form = $form = new CaConfigForm();
	$form->setIniConfig($this->Config())->handleRequest();
    }
}
