<?php namespace CmsGen\Schema;

use Criteria\CriteriaBuilder as ParamBuilder;

class Section extends ParamBuilder{


	const FIELD_KEY  = 'fields';
	const CONFIG_KEY = 'config';


	public function format()
	{

		return array_merge( $this['config']['settings']->format(), $this['fields']->format() );

	}


	public function setConfig( SectionConfig $config )
	{

		$this->addItem( $config->setName( self::CONFIG_KEY ) );
		return $this;

	}


	public function getConfig()
	{

		return $this->getItem( self::CONFIG_KEY );

	}


	public function configure( $callback )
	{

		$this->getConfig()->add( "settings", $callback );
		return $this;

	}


    public function setFields( Field $field )
    {

    	$this->addItem( $field->setName( self::FIELD_KEY ) );
    	return $this;

    }

    public function getFields()
    {

    	return $this->getItem( self::FIELD_KEY );

    }


	public function addField( $name, $callback )
	{

		$this->getFields()->add( $name, $callback );

		return $this;

	}

	public function getField( $name )
	{

		return @$this[ self::FIELD_KEY ][ $name ];

	}

}