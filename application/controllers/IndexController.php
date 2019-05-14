<?php

namespace Icinga\Module\Ca\Controllers;

use Icinga\Web\Controller;
use Icinga\Web\UrlParams;
use Icinga\Application\Config;
use Icinga\Authentication\Auth;

class IndexController extends Controller
{
    /** @var UrlParams */
    protected $params;

    protected $sudo = '/usr/bin/sudo';

    protected $runas = 'icinga';

    protected $icinga2bin = '/usr/sbin/icinga2';

    public function init()
    {
        $this->sudo  = Config::module('ca')->get('config', 'sudo') != null ?
			Config::module('ca')->get('config', 'sudo') :
			$this->sudo;
        $this->runas  = Config::module('ca')->get('config', 'runas') != null ?
			Config::module('ca')->get('config', 'runas') :
			$this->runas;
        $this->icinga2bin  = Config::module('ca')->get('config', 'icinga2') != null ?
			Config::module('ca')->get('config', 'icinga2') :
			$this->icinga2bin;
	$this->command = $this->sudo . " -u " . $this->runas . " " . $this->icinga2bin;
    }

    public function indexAction()
    {
	$auth = Auth::getInstance();
	if ($auth->hasPermission('ca/oper')){
            $this->view->authz=true;
	    $this->getTabs()->add(
                    'ca',
                    array(
        	        'label' => $this->translate('Certificate Authority'),
                        'title' => $this->translate('Certificate Authority'),
	                'url'   => $this->getRequest()->getUrl()->without('fingerprint')
                    ))->activate('ca');

	    if($this->params->isEmpty()) {
	         $this->view->calist = $this->parseIcingaCaList();
            } elseif ($this->params->has('fingerprint')) {
                 $this->view->sign = $this->signCertificate($this->params->shift('fingerprint'));
            } else {
                 $this->view->authz=false;
                 $this->view->authmsg=$this->translate("Invalid fingerprint.");
            }
	} else {
            $this->view->authz=false;
            $this->view->authmsg=
                 $this->translate("You do not have the permission to access CA.");
        }
    }

    public function signCertificate($fingerprint)
    {
	$command = $this->command . " ca sign $fingerprint";
	$output = shell_exec($command." 2>&1");
	return $output;
    }

    public function icinga2Version()
    {
		$command = $this->icinga2bin . " --version";
		$output = shell_exec($command." 2>&1");

		$temp = preg_split('/\n/', $output, -1, PREG_SPLIT_NO_EMPTY);
		$lines = preg_grep('/RLIMIT_/', $temp, PREG_GREP_INVERT);
		$lines = array_values($lines);
		# get first line
		$version = $lines[0];
		# Match version string
		if (preg_match('/r(\d+)\.(\d+)/', $version, $matches)) {
			$ret['major'] = $matches[1];
			$ret['minor'] = $matches[2];
			return $ret;
		} else {
			return;
		}
    }

    public function parseIcingaCaList()
    {
		# check version of icinga2 (https://github.com/nunofernandes/icingaweb2-module-ca/issues/6)
		$version = $this->icinga2Version();
		if (!empty($version) and !empty($version['major']) and !empty($version['minor'])) {
			if ($version['major'] == "2" and ((int)$version['minor'])<11) {
				$command = $this->command . " ca list";
			} else {
				$command = $this->command . " ca list --all";
			}
		} else { # fallback to the new defaults and hope for the best
			$command = $this->command . " ca list --all";
		}

		$output = shell_exec($command." 2>&1");
        $temp = preg_split('/\n/', $output, -1, PREG_SPLIT_NO_EMPTY);
        $lines = preg_grep('/RLIMIT_/', $temp, PREG_GREP_INVERT);
        $lines = array_values($lines);
	# remove first 2 elements
        unset($lines[0]);
        unset($lines[1]);

	$result = array();
        foreach ($lines as $line) {
            preg_match('/(\w{64})\s+\|\s+([^|]+)\s+\|\s+(\*?)\s*\|\s+CN\s+=\s+(.*)/', $line, $parsed, PREG_OFFSET_CAPTURE);
	    array_push($result, array(
			"fingerprint"  => $parsed[1][0],
	    		"timestamp"    => $parsed[2][0],
            		"signed"       => $parsed[3][0],
            		"subject"      => $parsed[4][0],
		) );
	}
	return $result;
    }
}
