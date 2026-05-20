<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;

// Choices.js einbinden (Joomla 4+)
try {
    $wa = Factory::getDocument()->getWebAssetManager();
    $wa->usePreset('choicesjs');
} catch (\Throwable $e) {
    // Fallback: native <select multiple> bleibt funktional
}

$subsets = json_decode($this->item['subsets'] ?? '', true);
if (!is_array($subsets)) {
    $subsets = [];
}
$filterType = Factory::getApplication()->input->getCmd('filter_type', 'category');
$filterTypeLabel = $filterType === 'agecategory' ? 'Alterskategorie' : 'Kategorie';
$existingFilters = isset($subsets['filters']) && is_array($subsets['filters']) ? $subsets['filters'] : [];
$existingStyle   = isset($subsets['style']) ? $subsets['style'] : 'dropdown';
$rawJson         = !empty($subsets) ? json_encode($subsets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';

// Verfügbare Optionen sammeln
$optCategories = [];
foreach ($this->categories as $category) {
    $optCategories[] = ['value' => $category['alias'], 'label' => $category['title'], 'full' => $category['title'] . ' (' . $category['alias'] . ')'];
}
$optAgecategories = [];
foreach ($this->agecategories as $agecategory) {
    $optAgecategories[] = ['value' => $agecategory['alias'], 'label' => $agecategory['title'], 'full' => $agecategory['title'] . ' (' . $agecategory['alias'] . ')'];
}
$optGenres = [];
foreach ($this->genres as $genre) {
    $optGenres[] = ['value' => $genre['alias'], 'label' => $genre['genre'], 'full' => $genre['genre'] . ' (' . $genre['alias'] . ')'];
}
$optPlayers = [];
foreach ($this->players as $player) {
    $alias = JApplicationHelper::stringURLSafe($player['players']);
    $optPlayers[] = ['value' => $alias, 'label' => $player['players'], 'full' => $player['players'] . ' (' . $alias . ')'];
}

$jsOptions = [
    'categories'    => $optCategories,
    'agecategories' => $optAgecategories,
    'genres'        => $optGenres,
    'players'       => $optPlayers,
];
?>
<style>
    .right { text-align: right !important; }
    .lupo-filter-card { border: 1px solid #ddd; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; background: #fafafa; }
    .lupo-filter-card .lupo-filter-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .75rem; gap: .5rem; }
    .lupo-filter-card .lupo-filter-head input[type=text] { flex: 1; font-weight: bold; }
    .lupo-filter-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .lupo-filter-grid label { display: block; font-size: .9em; color: #555; margin-bottom: .25rem; }
    .lupo-filter-grid select { width: 100%; }
    #lupo-advanced { margin-top: 1.5rem; }
    #lupo-advanced summary { cursor: pointer; font-weight: bold; padding: .5rem 0; }
    
    /* Joomla Dark Admin Theme (Bootstrap 5) */
    html[data-bs-theme="dark"] .lupo-filter-card {
        background: #20262d;
        border: 1px solid #333;
    }
    html[data-bs-theme="dark"] .lupo-filter-grid label {
        color: #fff;
    }
</style>

<div id="j-sidebar-container" class="j-sidebar-container j-sidebar-visible">
    <?php echo JHtmlSidebar::render(); ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">

    <h1><?= $this->item['title'] ?> <small style="font-size:.65em; color:#777;">(<?= $filterTypeLabel ?>)</small></h1>

    <form action="<?= JRoute::_('index.php?option=com_lupo&view=filter') ?>" method="post" id="adminForm" name="adminForm">

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="lupo-style"><strong>Filter-Style</strong></label>
            <select id="lupo-style" class="form-select" style="max-width: 240px;">
                <option value="dropdown" <?= $existingStyle === 'dropdown' ? 'selected' : '' ?>>Dropdown</option>
                <option value="buttons" <?= $existingStyle === 'buttons' ? 'selected' : '' ?>>Buttons</option>
            </select>
        </div>

        <div class="alert alert-info" style="margin-bottom: 1rem;">
            <strong>Was kann hier konfiguriert werden?</strong>
            <p style="margin: .5rem 0 0;">
                Auf dieser Seite können spezielle Filter definiert werden. Jede <em>Filter-Definition</em> entspricht einer Auswahlmöglichkeit. So könnte ein Filter "bis 7 Jahre" und einer "ab 8 Jahren" erstellt werden. Dann werden bei "bis 7 Jahre" alle Alterskategorien bis 7 Jahre ausgewählt, bei "ab 8 Jahre" die anderen. Damit kann der Besucher der Webseite schnell die passenden Spiele für mehrere Altersgruppen auswählen, ohne jedes Mal alle Alterskategorien einzeln anklicken zu müssen. Es können aber auch Filter für Kategorien, Genres oder Spieleranzahl erstellt werden – oder beliebige Kombinationen daraus.
            </p>
            <ul style="margin: .5rem 0 0 1.25rem;">
                <li>Über <strong>„Filter-Definition hinzufügen"</strong> können beliebig viele Filter angelegt werden; nicht benötigte können entfernt werden.</li>
                <li>Es können beliebige Kriterien aus <em>Kategorien</em>, <em>Alterskategorien</em>, <em>Genres</em> und <em>Spieleranzahl</em> pro Definition kombiniert werden.</li>
                <li>Pro Filter können in den vier Feldern die gewünschten Werte ausgewählt werden – leere Felder werden ignoriert.</li>
                <li>Werte innerhalb eines Feldes werden mit <strong>ODER</strong> verknüpft (z.B. "ab 3 Jahren" ODER "ab 4 Jahren" ODER "ab 5 Jahren" ...).</li>
                <li>Mit <strong>Filter-Style</strong> kann festgelegt werden, ob die Filter im Frontend als Dropdown oder als Buttons erscheinen.</li>
                <li>Wenn für die Kategorie ein Filter definiert ist, wird dieser verwendet. Andernfalls wird der Standardfilter genutzt, sofern dieser global aktiviert ist.</li>
                <li>Erfahrene Nutzer können das resultierende JSON unter <em>„Erweitert"</em> direkt einsehen oder bearbeiten.</li>
            </ul>
        </div>

        <h3>Filter-Definitionen</h3>
        <div id="lupo-filters-container"></div>

        <p>
            <button type="button" class="btn btn-success" id="lupo-add-filter">
                <span class="icon-plus"></span> Filter-Definition hinzufügen
            </button>
        </p>

        <details id="lupo-advanced">
            <summary>Erweitert: JSON direkt bearbeiten</summary>
            <p class="text-muted">
                Aktiviere die Checkbox, wenn das JSON in der Textarea unverändert gespeichert werden soll
                (GUI-Eingaben werden dann ignoriert). Andernfalls wird der Inhalt der Textarea beim
                Speichern aus der GUI neu generiert.
            </p>
            <p>
                <label>
                    <input type="checkbox" id="lupo-use-raw"> Textarea als Quelle verwenden (GUI ignorieren)
                </label>
            </p>
            <textarea name="subsets" id="subsets" style="width: 100%;" rows="20"><?= htmlspecialchars($rawJson, ENT_QUOTES, 'UTF-8') ?></textarea>
        </details>

        <input type="hidden" name="id" value="<?= $this->item['id'] ?>"/>
        <input type="hidden" name="filter_type" value="<?= $filterType ?>"/>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </form>

    <?php
    /* DEBUG-HILFEN
    <p><strong>Verfügbare Aliasse (Übersicht):</strong></p>
    <p>
        <em>Kategorien:</em>
        <?php $cats = []; foreach ($this->categories as $c) { $cats[] = $c['alias']; } ?>
        <code><?= implode(', ', $cats) ?></code>
    </p>
    <p>
        <em>Alterskategorien:</em>
        <?php $acs = []; foreach ($this->agecategories as $a) { $acs[] = $a['alias']; } ?>
        <code><?= implode(', ', $acs) ?></code>
    </p>
    <p>
        <em>Genres:</em>
        <?php $grs = []; foreach ($this->genres as $g) { $grs[] = $g['alias']; } ?>
        <code><?= implode(', ', $grs) ?></code>
    </p>
    <p>
        <em>Spieleranzahl:</em>
        <?php $pls = []; foreach ($this->players as $p) { $pls[] = JApplicationHelper::stringURLSafe($p['players']); } ?>
        <code><?= implode(', ', $pls) ?></code>
    </p>
    <?php */ ?>

</div>

<script>
(function () {
    var LUPO_OPTIONS = <?= json_encode($jsOptions) ?>;
    var existingFilters = <?= json_encode((object) $existingFilters) ?>;

    var container = document.getElementById('lupo-filters-container');
    var addBtn    = document.getElementById('lupo-add-filter');
    var styleSel  = document.getElementById('lupo-style');
    var textarea  = document.getElementById('subsets');
    var useRaw    = document.getElementById('lupo-use-raw');
    var form      = document.getElementById('adminForm');

    function buildSelect(type, selected) {
        var sel = document.createElement('select');
        sel.multiple = true;
        sel.className = 'form-select lupo-multi';
        sel.setAttribute('data-type', type);
        (LUPO_OPTIONS[type] || []).forEach(function (opt) {
            var o = document.createElement('option');
            o.value = opt.value;
            o.textContent = opt.label;
            if (opt.full) {
                o.setAttribute('data-full', opt.full);
            }
            if (selected && selected.indexOf(opt.value) !== -1) {
                o.selected = true;
            }
            sel.appendChild(o);
        });
        return sel;
    }

    function initChoices(sel) {
        if (typeof Choices === 'undefined') return;
        try {
            new Choices(sel, {
                removeItemButton: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: '– auswählen –',
                callbackOnCreateTemplates: function (template) {
                    return {
                        choice: function (classNames, data) {
                            var full = (data.customProperties && data.customProperties.full)
                                || (data.element && data.element.getAttribute && data.element.getAttribute('data-full'))
                                || data.label;
                            return template(
                                '<div class="' + String(classNames.item) + ' ' + String(classNames.itemChoice) + ' ' +
                                (data.disabled ? String(classNames.itemDisabled) : String(classNames.itemSelectable)) + '" ' +
                                'data-select-text="' + this.config.itemSelectText + '" ' +
                                'data-choice ' +
                                (data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable') + ' ' +
                                'data-id="' + data.id + '" ' +
                                'data-value="' + data.value + '" ' +
                                (data.groupId > 0 ? 'role="treeitem"' : 'role="option"') + '>' +
                                full +
                                '</div>'
                            );
                        }
                    };
                }
            });
        } catch (e) { /* ignore */ }
    }

    function buildCard(name, data) {
        data = data || {};
        var card = document.createElement('div');
        card.className = 'lupo-filter-card';

        var head = document.createElement('div');
        head.className = 'lupo-filter-head';

        var nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.className = 'form-control lupo-button-name';
        nameInput.placeholder = 'Filter-Name (z.B. "Für Kinder")';
        nameInput.value = name || '';

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger btn-sm';
        removeBtn.innerHTML = '<span class="icon-trash"></span> Entfernen';
        removeBtn.addEventListener('click', function () {
            card.parentNode.removeChild(card);
        });

        head.appendChild(nameInput);
        head.appendChild(removeBtn);
        card.appendChild(head);

        var grid = document.createElement('div');
        grid.className = 'lupo-filter-grid';

        ['categories', 'agecategories', 'genres', 'players'].forEach(function (type) {
            var wrap = document.createElement('div');
            var lbl = document.createElement('label');
            lbl.textContent = ({
                categories:    'Kategorien',
                agecategories: 'Alterskategorien',
                genres:        'Genres',
                players:       'Spieleranzahl'
            })[type];
            wrap.appendChild(lbl);
            var sel = buildSelect(type, Array.isArray(data[type]) ? data[type] : []);
            wrap.appendChild(sel);
            grid.appendChild(wrap);
            setTimeout(function () { initChoices(sel); }, 0);
        });

        card.appendChild(grid);
        container.appendChild(card);
    }

    addBtn.addEventListener('click', function () {
        buildCard('', {});
    });

    // Bestehende Daten laden
    var keys = Object.keys(existingFilters || {});
    if (keys.length === 0) {
        buildCard('', {});
    } else {
        keys.forEach(function (k) {
            buildCard(k, existingFilters[k]);
        });
    }

    // Beim Submit JSON aus GUI generieren (außer Nutzer überschreibt explizit)
    form.addEventListener('submit', function () {
        if (useRaw && useRaw.checked) {
            return; // Textarea unverändert übernehmen
        }

        var filters = {};
        var cards = container.querySelectorAll('.lupo-filter-card');
        cards.forEach(function (card) {
            var nameField = card.querySelector('.lupo-button-name');
            var name = (nameField && nameField.value || '').trim();
            if (!name) return;
            var entry = {};
            card.querySelectorAll('select.lupo-multi').forEach(function (sel) {
                var type = sel.getAttribute('data-type');
                var vals = Array.prototype.filter.call(sel.options, function (o) { return o.selected; })
                                                 .map(function (o) { return o.value; });
                if (vals.length > 0) {
                    entry[type] = vals;
                }
            });
            filters[name] = entry;
        });

        var result = {
            filters: filters,
            style: styleSel.value || 'dropdown'
        };

        textarea.value = JSON.stringify(result, null, 2);
    });
})();
</script>

