<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lupo
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Lupo Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_lupo
 * @since       3.43.0
 */
class LupoControllerFilter extends JControllerForm
{

    public function edit($key = null, $urlVar = null)
    {
        // set default view if not set
        $app         = JFactory::getApplication();
        $defaultview = $app->input->get->get('view', 'filter');
        $app->input->set('view', $defaultview);

        $app->input->set('layout', 'edit');

        // call parent behavior
        parent::display();

        echo $key;
    }


    public function cancel($key = null)
    {
        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_lupo&view=filters');
    }


    public function save($key = null, $urlVar = null)
    {
        $model = $this->getModel('Filter');
        $app = JFactory::getApplication();

        $input = JFactory::getApplication()->input;
        $data['subsets'] = $input->get('subsets','','RAW');
        $data['id'] = $input->get('id');
        $model->save($data);

        $app->enqueueMessage(JText::_('Erfolgreich gespeichert'), 'message');
        $app->redirect('index.php?option=com_lupo&view=filters');
    }
}