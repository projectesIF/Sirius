<?php

/**
 * Get available admin panel links
 *
 * @author Francesc Bassas i Bullich
 * @return array array of admin links
 */
class SiriusXtecMailer_Api_Admin extends Zikula_AbstractApi {
    public function getlinks() {
        $links = array();

        if (SecurityUtil::checkPermission('SiriusXtecMailer::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('SiriusXtecMailer', 'admin', 'testconfig'), 'text' => __('Test current settings'));
        }
        if (SecurityUtil::checkPermission('SiriusXtecMailer::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('SiriusXtecMailer', 'admin', 'modifyconfig'), 'text' => __('Settings'));
        }

        return $links;
    }

}