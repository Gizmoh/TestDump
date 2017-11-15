<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Hello World Component Controller
 *
 * @since  0.0.1
 */
class aprformController extends JControllerLegacy
{
    function LoadData()
    {
        $year = JRequest::getVar('year');
        $month = JRequest::getVar('month');
        $id = JRequest::getVar('id');
		$model = $this->getModel('aprform');
        $this->dataz = $model->loadForm($year,$month,$id);

        echo json_encode($this->dataz);

    }

    function SaveData()
    {
        $month = JRequest::getVar('month');
        $year = JRequest::getVar('year');
        $data = JRequest::getVar('data');
        $data = json_decode($data, true);
        $id = JRequest::getVar('id');

		$model = $this->getModel('aprform');
        $this->dataz = $model->saveForm($year,$month,$id,$data);

        echo json_encode($data);
        
    }

}
