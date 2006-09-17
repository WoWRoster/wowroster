<?php

// Certain things have to exist in the database after it has been created before the application, or specific parts of it, run.
class db_init extends Interface_Helper
{
	
	function rights_translation()
	{
		// Each right has a name and a description which will be displayed in a tooltip as explanation.
		$data = array('section_id' => 1,
					  'section_type' => LIVEUSER_SECTION_RIGHT,
					  'language_id' => 'enUS',
					  'name' => base64_encode('View'),
					  'description' => base64_encode('The right to see the content/information. <br /> (Read) <br /> Eg. Permission to see a page or specific parts of it.'));
		$this->add_translation($data);
		$data = array('section_id' => 2,
					  'section_type' => LIVEUSER_SECTION_RIGHT,
					  'language_id' => 'enUS',
					  'name' => base64_encode('Use'),
					  'description' => base64_encode('The right to use functionality. <br /> (Execute) <br /> Eg. Permission to create a user or place an order.'));
		$this->add_translation($data);
		$data = array('section_id' => 3,
					  'section_type' => LIVEUSER_SECTION_RIGHT,
					  'language_id' => 'enUS',
					  'name' => base64_encode('Edit'),
					  'description' => base64_encode('The right to edit rules/information. <br /> (Write) <br /> Eg. Permission to delete users. The most powerful right.'));
		$this->add_translation($data);
	}
	
}

?>