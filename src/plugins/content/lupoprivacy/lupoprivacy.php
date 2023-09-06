<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class plgContentLupoprivacy extends JPlugin {
	public function onContentPrepare($context, &$article, &$params, $limitstart = 0) {
		$paragraphs = [
			'general_introduction'          => 'Allgemeines / Einleitung',
			'processing_of_personal_data'   => 'Verarbeitung personenbezogener Daten',
			'cookies'                       => 'Cookies',
			'with_ssl_tls_encryption'       => 'Mit SSL/TLS-Verschlüsselung',
			'server_log_files'              => 'Server-Log-Dateien',
			'contact_form'                  => 'Kontaktformular / Formular für Spielreservationen',
			'newsletter'                    => 'Newsletter',
			'customer_login'                => 'Kundenlogin',
			'data_subject_rights'           => 'Rechte der betroffenen Person',
			'contradiction_email_marketing' => 'Widerspruch E-Mail Marketing',
			'copyrights'                    => 'Urheberrechte',
			'google_maps'                   => 'Google Maps',
			'google_analytics'              => 'Google Analytics',
			'google_webfonts'               => 'Google WebFonts',
			'youtube'                       => 'YouTube',
			'vimeo'                         => 'Vimeo',
			'data_transfer_to_the_usa'      => 'Datenübermittlung in die USA',
			'disclaimer'                    => 'Haftungsausschluss',
			'changes'                       => 'Änderungen',
		];

		$privacy_policy = '<div class="uk-accordion uk-margin-large-top" data-uk-accordion>';
		foreach ($paragraphs as $key => $paragraph_title) {
			$show = $this->params->get($key, '1');
			if($show == '1'){
				$content = file_get_contents(__DIR__ .'/de_' . $key . '.html');
				$privacy_policy .='<h3 class="uk-accordion-title">'.$paragraph_title.'</h3>';
				$privacy_policy .='<div class="uk-accordion-content">' . nl2br($content) . '</div>';
			}
		}
		$privacy_policy .= '</div>';

		 $privacy_policy .= '<div class="uk-margin-top"><strong>Quellen</strong>: <a style="color: inherit; text-decoration: none;" href="https://brainbox.swiss/" rel="nofollow">BrainBox Solutions</a> / databauer</div>';

		$article->text = str_replace('[datenschutz]', $privacy_policy, $article->text);

		return true;
	}

}

