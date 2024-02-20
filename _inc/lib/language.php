<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	MODERN POS
| -----------------------------------------------------
| AUTHOR:			ITSOLUTION24.COM
| -----------------------------------------------------
| EMAIL:			info@itsolution24.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY ITSOLUTION24.COM
| -----------------------------------------------------
| WEBSITE:			http://itsolution24.com
| -----------------------------------------------------
*/
class Language 
{
	private $default = 'en';
	private $code;
	private $lang_id;
	private $data;
	private $lang_code;
private $lang_name;

	public function __construct($lang_code) 
	{
		$this->lang_code = $lang_code;
		$statement = db()->prepare("SELECT `id` FROM `languages` WHERE `code` = ?");
      	$statement->execute(array($this->lang_code));
      	$lang = $statement->fetch(PDO::FETCH_ASSOC);
      	if (!$lang) {
      		$statement = db()->prepare("SELECT `id` FROM `languages` WHERE `code` = ?");
	      	$statement->execute(array('en'));
	      	$lang = $statement->fetch(PDO::FETCH_ASSOC);
      	} 
      	$this->lang_id = $lang['id'];
	}

	public function get($key) 
	{
		if (!isset($this->data[$key])) {
			$statement = db()->prepare("SELECT `id` FROM `language_translations` WHERE `lang_id` = ? AND `lang_key` = ?");
      		$statement->execute(array($this->lang_id, $key));
      		$lang = $statement->fetch(PDO::FETCH_ASSOC);
      		if (!$lang) {
      			$statement = db()->prepare("INSERT INTO `language_translations` (lang_id, lang_key) VALUES(?, ?)");
				$statement->execute(array($this->lang_id, $key));
      		}
		}

		if (isset($this->data[$key]) && $this->data[$key]) {
			return html_entity_decode($this->data[$key]);
		}

		$key1 = str_replace(array('_', 'menu', 'label', 'text', 'button', 'title', 'success', 'hint', 'placeholder'), ' ', $key);
		return isset($this->data[$key]) && $this->data[$key] ? html_entity_decode($this->data[$key]) : ucwords($key1);
		// return $key;
	}
	public function load() 
	{
		if (empty($this->data)) {
			$statement = db()->prepare("SELECT `lang_key`, `lang_value` FROM `language_translations` WHERE `lang_id` = ?");
			$statement->execute(array($this->lang_id));
			foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
				// Verificar si el valor no es nulo antes de llamar a trim()
				$trimmed_key = $value['lang_key'] !== null ? trim($value['lang_key']) : null;
				$trimmed_value = $value['lang_value'] !== null ? trim($value['lang_value']) : null;
				
				// Asegurarse de no agregar valores nulos al arreglo
				if ($trimmed_key !== null && $trimmed_value !== null) {
					$this->data[$trimmed_key] = $trimmed_value;
				}
			}
		}
	}
	


    }



