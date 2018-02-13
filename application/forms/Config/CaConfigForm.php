<?php

namespace Icinga\Module\Ca\Forms\Config;

use Icinga\Forms\ConfigForm;

class CaConfigForm extends ConfigForm
{
    public function init()
    {
        $this->setName('form_config_ca');
        $this->setSubmitLabel($this->translate('Save Changes'));
    }

    public function createElements(array $formData)
    {
        $this->addElements([
            [
                'text',
                'config_runas',
                [
                    'required'      => true,
                    'label'         => $this->translate('Run icingacli as user'),
                ]
            ],
            [
                'text',
                'config_sudo',
                [
                    'required'      => true,
                    'label'         => $this->translate('Sudo path'),
                ]
            ],
            [
                'text',
                'config_icinga2',
                [
                    'required'      => true,
                    'label'             => $this->translate('icinga2 binary path'),
                ]
            ],
        ]);
    }
}
