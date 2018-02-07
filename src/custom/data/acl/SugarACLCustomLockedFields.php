<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2018-02-07 on Sugar 7.10.0.0
//
// Custom extension of acl SugarACLLockedFields, to only perform the locked field runtime lookup if Advanced Workflows are active for the said module

class SugarACLCustomLockedFields extends SugarACLLockedFields
{
    protected static $active_awf;

    protected static function hasActiveAWFForModule($module = '')
    {
        if (!empty($module)) {
            if (isset(self::$active_awf)) {
                $awfs = self::$active_awf;
            } else {
                $awfs = self::getActiveAWFsFromDb();
                self::$active_awf = $awfs;
            }
            if (!empty($awfs) && !empty($awfs[$module])) {
                return true;
            }
        }
        return false;
    }

    protected static function getActiveAWFsFromDb()
    {
        $awfProject = BeanFactory::newBean('pmse_Project');
        $q = new SugarQuery();
        $q->from($awfProject, array('team_security' => false));
        $q->select(array('id', 'prj_module', 'prj_status'));
        $q->where()->equals('prj_status', 'ACTIVE');
        $results = $q->execute();
        
        $processes = array();
        if (!empty($results)) {
            foreach ($results as $result) {
                if (empty($processes[$result['prj_module']])) {
                //if ($result['prj_status'] == 'ACTIVE' && empty($processes[$result['prj_module']])) {
                    $processes[$result['prj_module']] = true;
                }
            }
        }

        return $processes;
    }

    public function checkAccess($module, $view, $context)
    {
        if ($view != 'field') {
            return true;
        }

        if (empty($context) || empty($context['field']) || empty($context['action'])) {
            return true;
        }

        if (!self::hasActiveAWFForModule($module)) {
            return true;
        } else {
            // call parent if it needs to be executed
            return parent::checkAccess($module, $view, $context);
        }
    }
}
