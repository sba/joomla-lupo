<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

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

        if($data['subsets']!="") {
            $json = json_decode($data['subsets']);
            if ($json !== null) {
                $model->save($data);
                $app->enqueueMessage(JText::_('Erfolgreich gespeichert'), 'message');
            } else {
                $app->enqueueMessage(JText::_('Ungültiges JSON, nicht gespeichert'), 'error');
            }
        } else {
	        $model->save($data);
	        $app->enqueueMessage(JText::_('JSON gelöscht'), 'message');
        }

        $app->redirect('index.php?option=com_lupo&view=filters');
    }
}