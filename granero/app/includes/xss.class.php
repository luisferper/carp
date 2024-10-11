<?php

CLass XSS {

	     public function __construct(){
             // The Construct
	     }

         private $allow_http_value = false;
         private $string;
         private $evilStrings = array(

		'!(&#0+[0-9]+)!' => '$1;',
		'/(&#*\w+)[\x00-\x20]+;/u' => '$1;>',
		'/(&#x*[0-9A-F]+);*/iu' => '$1;',
		'#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu' => '$1>',
		'#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2nojavascript...',
		'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2novbscript...',
		'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u' => '$1=$2nomozbinding...',
		'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i' => '$1>',
		'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu' => '$1>',
		'#</*\w+:\w[^>]*+>#i' => '',
		'#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i' => '',
		'\'' => '&apos;',
		'"' => '&quot;',
		'&' => '&amp;',
		'<' => '&lt;',
		'>' => '&gt;',
		'SELECT * FROM' => '',
		'SELECT(' => '',
		'SLEEP(' => '',
		'AND (' => '',
		' AND' => '',
		'(CASE' => ''
         );

	     public function allow_http(){
		     $this->allow_http_value = true;
	     }

	     public function disallow_http(){
		     $this->allow_http_value = true;
	     }

	     public function XSString($string){
		     $this->string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
		     $this->replaceXSS();
		     $blank = $this->string;
		     if (empty($blank)) 
			     return null;
		     else
                 return $this->string;
	     }

	     private function replaceXSS(){
		     $this->string = str_replace(array('&amp;', '&lt;', '&gt;', '&apos;'), array('', '', '',''), $this->string);
		     if($this->allow_http_value === false){
			     $this->string = str_replace(array('&', '%', 'script', 'localhost'), array('', '', '', ''), $this->string);
		     }
		     else
		     {
			     $this->string = str_replace(array('&', '%', 'script', 'localhost', '../'), array('', '', '', '', ''), $this->string);
		     }
		     foreach($this->evilStrings as $pattern => $replacement){
			     $this->string = str_replace($pattern,$replacement,$this->string);
		     }
	     }

} 