<?php

class Page extends SiteTree
{
    private static $db = array(
		'ShowFacebookFeed' => 'Boolean'
    );

    private static $has_one = array(
    );
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', CheckboxField::create('ShowFacebookFeed'));
		return $fields;
	}
}
