<?php namespace CmsGen\Schema;

use RicAnthonyLee\Itemizer\Item;

class FieldRegistrar {


	protected $section;


	protected $fieldTypes;


	protected $allowTypes = [ 'text', 'textarea', 'image', 'upload', 'checkbox', 'wysiwyg', 'separator', 'hasOne', 'hasMany', 'date', 'gallery' ];


	public function setSection( Section $section )
	{

		$this->section = $section;
		return $this;

	}


	public function getSection()
	{

		return $this->section;

	}


	public function setFieldTypes( FieldTypes $types )
	{

		$this->fieldTypes = $types;
		return $this;

	}


	public function getFieldTypes()
	{

		return $this->fieldTypes;

	}

	public function getAllowedTypes()
	{

		return $this->allowTypes;

	}


	public function register( $type, $name, $label, $order = 0, array $settings = array() )
	{

		if( !in_array( $type, $this->getAllowedTypes() ) ) 
			throw new \InvalidArgumentException( "type: $type, is invalid " );
		

		$field = $this->getSection()->addField( 
			$name,
			$this->getFieldTypes()->{ $type }( $name, $label, $order, $settings )
		)
		->getField( $name );


		foreach( $settings as $k => $s )
		{

			$field->add( new Item( $k, false, $s ) );

		}

		return $this;

	}

}