<?php

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Editor\Button\Button;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Event\Editor\EditorButtonsSetupEvent;
use Joomla\Event\SubscriberInterface;

class plgButtonLupotoy extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return array
     *
     * @since   5.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return ['onEditorButtonsSetup' => 'onEditorButtonsSetup'];
    }

    /**
     * @param  EditorButtonsSetupEvent $event
     * @return void
     *
     * @since   5.0.0
     */
    public function onEditorButtonsSetup(EditorButtonsSetupEvent $event): void
    {
        $subject  = $event->getButtonsRegistry();
        $disabled = $event->getDisabledButtons();

        if (in_array($this->_name, $disabled)) {
            return;
        }

        $button = $this->onDisplay($event->getEditorId());

        if ($button) {
            $subject->add($button);
        }
    }

    /**
     * Display the button.
     */
    public function onDisplay($editor)
    {
        $app = Factory::getApplication();

        if ($app->isClient('site')) {
            return;
        }

        // Ensure language is loaded in the administrator context.
	    // Dies ist irgendwie notwendig hier...sonst wird der Button nicht übersetzt
        $lang = Factory::getLanguage();
        $lang->load('plg_editors-xtd_lupotoy', JPATH_ADMINISTRATOR, null, false, true);

        $link = 'index.php?option=com_lupo&view=toyselect&tmpl=component&layout=modal';

        $button = new Button($this->_name);
        $button->set('modal', true)
            ->set('link', $link)
            ->set('text', Text::_('PLG_EDITORS_LUPOTOY_BUTTON_TEXT'))
            ->set('name', $this->_name)
            ->set('class', 'btn')
            ->set('icon', 'puzzle')
            ->set('options', [
                'onSelect' => 'jSelectToy',
                'footer' => '<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
            ]);

        // Add the selection script
        $doc = Factory::getDocument();
        $doc->addScriptDeclaration('
            function getShortcodeCandidateFromTinyMCE(ed, doSelect) {
                try {
                    var selText = ed.selection.getContent({ format: "text" });
                    if (selText && selText.indexOf("[lupo") !== -1) return selText;
                    var rng = ed.selection.getRng();
                    if (rng && rng.startContainer) {
                        var node = rng.startContainer;
                        var text = node.nodeType === 3 ? node.data : (node.textContent || "");
                        var offset = rng.startOffset || 0;
                        if (typeof text === "string") {
                            // Find [ preceding the cursor
                            var start = text.lastIndexOf("[", offset);
                            // Find ] following the cursor
                            var end = text.indexOf("]", offset);
                            
                            // If no [ found before offset, or ] is before [, try searching further
                            if (start === -1 || (end !== -1 && end < start)) {
                                 // maybe cursor is after the shortcode or inside another one
                            }

                            // If cursor is inside [ ]
                            if (start !== -1 && end !== -1 && end > start && start <= offset && end >= offset - 1) {
                                var match = text.substring(start, end + 1);
                                if (match.indexOf("[lupo") !== -1) {
                                    if (doSelect && node.nodeType === 3) {
                                        var newRng = ed.dom.createRng();
                                        newRng.setStart(node, start);
                                        newRng.setEnd(node, end + 1);
                                        ed.selection.setRng(newRng);
                                    }
                                    return match;
                                }
                            }
                            
                            // If cursor is exactly after ]
                            if (offset > 0 && text.charAt(offset-1) === "]") {
                                var lastStart = text.lastIndexOf("[", offset-1);
                                if (lastStart !== -1) {
                                    var match = text.substring(lastStart, offset);
                                    if (match.indexOf("[lupo") !== -1) {
                                        if (doSelect && node.nodeType === 3) {
                                            var newRng = ed.dom.createRng();
                                            newRng.setStart(node, lastStart);
                                            newRng.setEnd(node, offset);
                                            ed.selection.setRng(newRng);
                                        }
                                        return match;
                                    }
                                }
                            }

                            // Search whole string for [lupo ...] if cursor is "near"
                            var re = /\\\[lupo[^\\\]]+\\\]/gi;
                            var m;
                            while ((m = re.exec(text)) !== null) {
                                if (offset >= m.index && offset <= m.index + m[0].length) {
                                    if (doSelect && node.nodeType === 3) {
                                        var newRng = ed.dom.createRng();
                                        newRng.setStart(node, m.index);
                                        newRng.setEnd(node, m.index + m[0].length);
                                        ed.selection.setRng(newRng);
                                    }
                                    return m[0];
                                }
                            }
                        }
                    }
                } catch (e) {}
                return "";
            }

            function jSelectToy(tag) {
                var inserted = false;
                // Try to replace existing shortcode if we found one
                if (window.tinymce && tinymce.get("' . $editor . '")) {
                    var ed = tinymce.get("' . $editor . '");
                    var cand = getShortcodeCandidateFromTinyMCE(ed, true); // true to select it
                    if (cand) {
                        ed.selection.setContent(tag);
                        inserted = true;
                    }
                }
                
                if (!inserted) {
                    jInsertEditorText(tag, "' . $editor . '");
                }
                
                // Try to find the modal more robustly
                var modalElement = document.querySelector("#modal-' . $this->_name . '");
                
                // Fallback: If not found by ID, try finding it by the iframe it contains
                if (!modalElement) {
                    var iframes = document.querySelectorAll("iframe");
                    for (var i = 0; i < iframes.length; i++) {
                        if (iframes[i].contentWindow === window || iframes[i].src.indexOf("view=toyselect") !== -1) {
                            modalElement = iframes[i].closest(".modal");
                            break;
                        }
                    }
                }
                
                // Fallback: Just try to find ANY open bootstrap modal
                if (!modalElement) {
                    modalElement = document.querySelector(".modal.show");
                }

                if (modalElement) {
                    var modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (!modalInstance) {
                        // Sometimes getInstance fails if not initialized via JS, try to create it
                        modalInstance = new bootstrap.Modal(modalElement);
                    }
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }

            (function() {
                var modalId = "#modal-' . $this->_name . '";
                var baseLink = "' . $link . '";
                var editorId = "' . $editor . '";

                function findToyShortcodeAroundCursor() {
                    // Try TinyMCE first (works also in JCE)
                    if (window.tinymce && tinymce.get(editorId)) {
                        var inst = tinymce.get(editorId);
                        var cand = getShortcodeCandidateFromTinyMCE(inst, false);
                        if (cand) return cand;
                    }
                    // Fallback JCE API - selection only
                    if (window.WFEditor && WFEditor.getSelectedText) {
                        try {
                            var t = WFEditor.getSelectedText(editorId);
                            if (t) return t;
                        } catch (e) {}
                    }
                    return "";
                }

                function parseToyShortcode(str) {
                    if (!str) return null;
                    console.log("LUPO: Parsing string", str);
                    
                    // Direct approach instead of complex regex
                    var cleaned = str.trim();
                    if (!cleaned.toLowerCase().startsWith("[lupo")) {
                        console.log("LUPO: String does not start with [lupo");
                        return null;
                    }
                    
                    // Remove leading [lupo and trailing ]
                    var attrString = cleaned.replace(/^\\\[lupo[\\s\\u00A0]+/i, "").replace(/\\\]$/, "");
                    console.log("LUPO: Attribute string to parse", attrString);
                    
                    var attrs = {};
                    // Improved regex: group 1=key, group 2=quoted value, group 3=key, group 4=unquoted value
                    var attrRe = /([a-zA-Z0-9]+)[\\s\\u00A0]*=[\\s\\u00A0]*["\']([^"\']*)["\']|([a-zA-Z0-9]+)[\\s\\u00A0]*=[\\s\\u00A0]*([^\\s\\u00A0\\\]]+)/g;
                    var amatch;
                    while ((amatch = attrRe.exec(attrString)) !== null) {
                        var key = (amatch[1] || amatch[3]).toLowerCase();
                        var val = amatch[2] || amatch[4] || "";
                        attrs[key] = val;
                    }
                    console.log("LUPO: Extracted attributes object", attrs);

                    var spiele = attrs.spiele || attrs.spiel || "";
                    if (!spiele) {
                        console.log("LUPO: No spiele/spiel attribute found in", attrs);
                        return null;
                    }

                    var number = spiele
                        .replace(/&nbsp;/g, " ")
                        .replace(/[ ,\\u00A0]+/g, ";")
                        .replace(/;+$/, "");
                    
                    var layout  = attrs.layout || "";
                    var isTable = ["tabelle", "table", "tabella", "tableau"].indexOf(layout.toLowerCase()) !== -1;
                    var nolink  = attrs.nolink || 0;
                    var columns = attrs.columns || 2;
                    
                    var result = { 
                        number: number, 
                        table: isTable ? 1 : 0, 
                        layout: layout,
                        nolink: nolink,
                        columns: columns
                    };
                    console.log("LUPO: Final parsed object", result);
                    return result;
                }

                document.addEventListener("shown.bs.modal", function(e) {
                    var modalEl = e.target;
                    console.log("LUPO: Modal shown event", modalEl.id);
                    if (!modalEl || !modalEl.id || modalEl.id.indexOf("lupotoy") === -1) return;

                    var candidate = findToyShortcodeAroundCursor();
                    console.log("LUPO: Candidate text", candidate);
                    var shortcode = parseToyShortcode(candidate);
                    console.log("LUPO: Parsed shortcode", shortcode);
                    if (!shortcode || !shortcode.number) return;

                    var iframe = modalEl.querySelector("iframe");
                    if (!iframe) {
                        console.log("LUPO: No iframe found in modal");
                        return;
                    }

                    var url = baseLink + "&number=" + encodeURIComponent(shortcode.number) 
                        + (shortcode.table ? "&table=1" : "")
                        + (shortcode.layout ? "&layout=" + encodeURIComponent(shortcode.layout) : "")
                        + (shortcode.nolink ? "&nolink=" + encodeURIComponent(shortcode.nolink) : "")
                        + (shortcode.columns ? "&columns=" + encodeURIComponent(shortcode.columns) : "");
                    console.log("LUPO: Setting iframe src to", url);
                    
                    // Force reload if same URL or just set it
                    if (iframe.src.indexOf(url) === -1) {
                        iframe.src = url;
                    }
                });
            })();
        ');

        return $button;
    }
}
