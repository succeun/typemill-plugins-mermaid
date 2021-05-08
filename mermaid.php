<?php

namespace Plugins\Mermaid;

use \Typemill\Plugin;

class Mermaid extends Plugin
{
	protected $settings;
	
    public static function getSubscribedEvents()
    {
		return array(
			'onSettingsLoaded'		=> 'onSettingsLoaded',		
			'onTwigLoaded' 			=> 'onTwigLoaded'
		);
    }
	
	public function onSettingsLoaded($settings)
	{
		$this->settings = $settings->getData();
	}
	
	
	public function onTwigLoaded()
	{
		$mermaidSettings = $this->settings['settings']['plugins']['mermaid'];
		
		if (isset($mermaidSettings['theme'])) {
			$theme = $mermaidSettings['theme'];
		} else {
			$theme = 'default';
		}
		
		if (isset($mermaidSettings['securityLevel'])) {
			$securityLevel = $mermaidSettings['securityLevel'];
		} else {
			$securityLevel = 'strict';
		}
		
		if (isset($mermaidSettings['htmlLabels'])) {
			$htmlLabels = $mermaidSettings['htmlLabels'];
		} else {
			$htmlLabels = 'true';
		}
		
		if (isset($mermaidSettings['fontFamily'])) {
			$fontFamily = $mermaidSettings['fontFamily'];
		} else {
			$fontFamily = '';
		}
		
		$this->addJS('/mermaid/public/mermaid.min.js');
	
		/* initialize the script */
		$this->addInlineJS('
		document.addEventListener("DOMContentLoaded", function() {
			document.querySelectorAll("code.language-mermaid").forEach(function(element, index) {
				var content = element.innerHTML.replace(/&amp;/g, "&");
				tempDiv = document.createElement("div");
				tempDiv.className = "mermaid";
				tempDiv.align = "center";
				tempDiv.innerHTML = content;
				element.parentNode.parentNode.replaceChild(tempDiv, element.parentNode);
			});
		});
		');
		
		$this->addInlineJS("mermaid.initialize({'theme': '".$theme."', 'securityLevel': '".$securityLevel."', 'htmlLabels': ".$htmlLabels.", 'fontFamily': '".$fontFamily."'});");
	}
}