<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

HTMLHelper::_('behavior.core');

$input  = Factory::getApplication()->input;
$number  = $input->getString('number', '');
$table   = $input->getInt('table', 0);
$layout  = $input->getString('layout', '');
$nolink  = $input->getInt('nolink', 0);
$columns = $input->getInt('columns', 2);

// Handle table parameter for backward compatibility if layout is not set
if (empty($layout) && !empty($table)) {
    $layout = 'tabelle';
}
if (empty($layout)) {
    $layout = 'image';
}
?>
<style>
    /* Override Joomla/Bootstrap default modal height for this small popup */
    body.component {
        height: auto;
    }
    .modal-content {
        height: auto !important;
        min-height: 200px;
    }
</style>
<script>
    function insertFromForm() {
        var numberInput = document.getElementById('lupo-toy-number');
        var layoutSelect = document.getElementById('lupo-toy-layout');
        var nolinkCheckbox = document.getElementById('lupo-toy-nolink');
        var columnsSelect = document.getElementById('lupo-toy-columns');
        if (!numberInput) return;

        var number = (numberInput.value || '').trim();
        if (!number) {
            alert('<?php echo Text::_('COM_LUPO_TOY_NUMBER'); ?>');
            numberInput.focus();
            return;
        }

        var layout = layoutSelect ? layoutSelect.value : 'image';
        var noLink = nolinkCheckbox && nolinkCheckbox.checked;
        var columns = columnsSelect ? columnsSelect.value : '2';
        var tag = '[lupo spiele="' + number + '"' + (layout !== 'image' ? ' layout="' + layout + '"' : '') + (noLink ? ' nolink="1"' : '') + (columns !== '2' ? ' columns="' + columns + '"' : '') + ']';

        if (window.parent && typeof window.parent.jSelectToy === 'function') {
            window.parent.jSelectToy(tag);
        }
    }

    function closeModal() {
        if (window.parent && typeof window.parent.bootstrap !== 'undefined') {
            // Find ANY open bootstrap modal in parent
            var modalElement = window.parent.document.querySelector('.modal.show');
            if (modalElement) {
                var modalInstance = window.parent.bootstrap.Modal.getInstance(modalElement);
                if (!modalInstance) {
                    modalInstance = new window.parent.bootstrap.Modal(modalElement);
                }
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }
    }

    function toggleLayoutFields() {
        var layoutSelect = document.getElementById('lupo-toy-layout');
        var nolinkGroup = document.getElementById('lupo-toy-nolink-group');
        var columnsGroup = document.getElementById('lupo-toy-columns-group');

        if (!layoutSelect) return;

        var isImage = layoutSelect.value === 'image';
        if (nolinkGroup) nolinkGroup.style.display = isImage ? 'block' : 'none';
        if (columnsGroup) columnsGroup.style.display = isImage ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleLayoutFields();
        var layoutSelect = document.getElementById('lupo-toy-layout');
        if (layoutSelect) {
            layoutSelect.addEventListener('change', toggleLayoutFields);
        }
    });
</script>

<div class="container-popup p-3" style="min-width: 260px; max-width: 320px;">
    <div class="control-group mb-3">
        <label class="control-label" for="lupo-toy-number"><?php echo Text::_('COM_LUPO_TOY_NUMBER'); ?></label>
        <div class="controls">
            <input type="text" id="lupo-toy-number" class="form-control" placeholder="1234 oder 1234.1" value="<?php echo htmlspecialchars($number ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
    </div>

    <div class="control-group mb-3">
        <label class="control-label" for="lupo-toy-layout"><?php echo Text::_('COM_LUPO_LAYOUT_LABEL'); ?></label>
        <div class="controls">
            <select id="lupo-toy-layout" class="form-select">
                <option value="image" <?php echo $layout === 'image' ? 'selected' : ''; ?>><?php echo Text::_('COM_LUPO_LAYOUT_IMAGE'); ?></option>
                <option value="tabelle" <?php echo in_array($layout, ['tabelle', 'table', 'tabella', 'tableau']) ? 'selected' : ''; ?>><?php echo Text::_('COM_LUPO_LAYOUT_TABLE'); ?></option>
                <option value="full" <?php echo $layout === 'full' ? 'selected' : ''; ?>><?php echo Text::_('COM_LUPO_LAYOUT_FULL'); ?></option>
            </select>
        </div>
    </div>

    <div id="lupo-toy-nolink-group" class="control-group form-check mb-2">
        <input type="checkbox" id="lupo-toy-nolink" class="form-check-input" <?php echo !empty($nolink) ? 'checked' : ''; ?> />&nbsp;
        <label class="form-check-label" for="lupo-toy-nolink">Bild nicht verlinken</label>
    </div>

    <div id="lupo-toy-columns-group" class="control-group mb-4">
        <label class="control-label" for="lupo-toy-columns">Anzeige in Spalten</label>
        <div class="controls">
            <select id="lupo-toy-columns" class="form-select">
                <option value="1" <?php echo $columns == 1 ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo $columns == 2 ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo $columns == 3 ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo $columns == 4 ? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo $columns == 5 ? 'selected' : ''; ?>>5</option>
                <option value="6" <?php echo $columns == 6 ? 'selected' : ''; ?>>6</option>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" onclick="insertFromForm()">
            <?php echo Text::_('COM_LUPO_INSERT_SHORTTAG'); ?>
        </button>
        <button type="button" class="btn btn-secondary" onclick="closeModal()">
            <?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?>
        </button>
    </div>
</div>
