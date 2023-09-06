<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class plgContentLuporandomquote extends JPlugin
{
    public function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {
        $quotes   = $this->params->get('quotes_normal', "");
        $template = $this->params->get('quotes_template', "");

        $quotesplit = explode("\r\n", $quotes);
        $quotesplit = array_filter($quotesplit, function ($var) {
            return trim($var) != '';
        });

        $quotesplit = array_values($quotesplit);
        $random     = rand(0, count($quotesplit) - 1);

        $quote_row = $quotesplit[$random];

        $pos        = strpos($quote_row, ";");
        $quote      = substr($quote_row, 0, $pos);
        $quote_name = substr($quote_row, $pos + 1);

        if ($pos === false) {
            $quote      = $quote_row;
            $quote_name = "Unbekannt";
        }

        $quote_together = str_replace('[quote]', $quote, $template);
        $quote_together = str_replace('[name]', $quote_name, $quote_together);

        $article->text = str_replace('[zufallszitat]', $quote_together, $article->text);

        return true;
    }

}

