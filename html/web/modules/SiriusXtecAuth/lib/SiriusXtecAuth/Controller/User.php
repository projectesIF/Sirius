<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

class SiriusXtecAuth_Controller_User extends Zikula_AbstractController
{
	public function logingXtecApps($args)
	{
		$uname = FormUtil::getPassedValue('uname', isset($args['uname']) ? $args['uname'] : '', 'REQUEST');
		$pass = FormUtil::getPassedValue('pass', isset($args['pass']) ? $args['pass'] : '', 'REQUEST');
		$pass = urldecode(base64_decode($pass));	
		$logtype = FormUtil::getPassedValue('logtype', isset($args['logtype']) ? $args['logtype'] : '', 'REQUEST');
		if ($logtype == 'in') {
			$sctime = ModUtil::getVar('SiriusXtecAuth','loginTime',0);
		} elseif ($logtype == 'out') {
			$sctime = ModUtil::getVar('SiriusXtecAuth','logoutTime',0);
		} else {
			$sctime = 0;
		}
		//$val = 'http://' . $uname . ':' . $pass . '@';
                $val1 = ModUtil::getVar('SiriusXtecAuth','gtafProtocol',false) . '://' . $uname . ':' . $pass . '@';
                $val2 = ModUtil::getVar('SiriusXtecAuth','e13Protocol',false) . '://' . $uname . ':' . $pass . '@';
        $this->view->assign('gtafLogin', $val1 . ModUtil::getVar('SiriusXtecAuth','gtafURL',false));
        $this->view->assign('e13Login', $val2 . ModUtil::getVar('SiriusXtecAuth','e13URL',false));
        $this->view->assign('logoutXtecApps', ModUtil::getVar('SiriusXtecAuth','logoutXtecApps',false));
		$this->view->assign('sctime',$sctime);
		$this->view->assign('logtype',$logtype);
        return $this->view->fetch('SiriusXtecAuth_XtecAppslogin.tpl');
	}
}
