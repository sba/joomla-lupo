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
    public function __construct($config = array())
    {
        parent::__construct($config);
        // Override default mapping where 'apply' is routed to 'save'
        $this->registerTask('apply', 'apply');
    }

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
        $this->doSave();

        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_lupo&view=filters');
    }

    public function apply($key = null, $urlVar = null)
    {
        $this->doSave();

        $app = JFactory::getApplication();
        $id  = JFactory::getApplication()->input->get('id');
        $app->redirect('index.php?option=com_lupo&view=filter&layout=edit&id=' . $id);
    }

    protected function doSave()
    {
        $model = $this->getModel('Filter');
        $app   = JFactory::getApplication();

        $input           = $app->input;
        $data['subsets'] = $input->get('subsets', '', 'RAW');
        $data['id']      = $input->get('id');

        if ($data['subsets'] != "") {
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
    }
}