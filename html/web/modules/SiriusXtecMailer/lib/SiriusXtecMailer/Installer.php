<?php

class SiriusXtecMailer_Installer extends Zikula_AbstractInstaller {

    /**
     * initialise the module
     *
     * @author Francesc Bassas i Bullich
     * @return bool true on success, false otherwise
     */
    public function Install() {

        // Set default module variables
        $this->setVar('enabled', 0)
                ->setVar('idApp', 'SIRIUS')
                ->setVar('replyAddress', System::getVar('adminmail'))
                ->setVar('sender', 'educacio')
                ->setVar('environment', 'PRO') // Referent a l'entorn (INT, ACC, PRO, FRM)
                ->setVar('contenttype', 2)
                ->setVar('log', 0)
                ->setVar('debug', 0)
                ->setVar('logpath', '');

        EventUtil::registerPersistentModuleHandler('SiriusXtecMailer', 'module.mailer.api.sendmessage', array('SiriusXtecMailer_Listeners', 'sendMail'));

        // Initialisation successful
        return true;
    }

    /**
     * delete the module
     *
     * @author  Francesc Bassas i Bullich
     * @return  bool true if successful, false otherwise
     */
    public function uninstall() {
        // Delete all module variables
        $this->delVar('SiriusXtecMailer');

        EventUtil::unregisterPersistentModuleHandler('SiriusXtecMailer');

        // Deletion successful
        return true;
    }

    public function upgrade($oldversion) {
        return true;
    }

}