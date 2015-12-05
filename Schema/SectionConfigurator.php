<?php namespace CmsGen\Schema;

use CmsGen\Schema\FieldTypes;


class SectionConfigurator{



	public function getSystemFields()
	{

		return [
			"num"               => "id",
			"createdDate"       => "Created",
			"createdByUserNum"  => "Created By",
			"updatedDate"       => "Last Updated",
			"updatedByUserNum"  => "Last Updated By",
			"dragSortOrder"     => "Order"
		];

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

	public function configure( Section $section )
	{

		$this->setSettings( $section )
		     ->addSystemFields( $section );

		return $section;

	}

	protected function setSettings( Section $section )
	{

		$section->configure( function( $config, $param ){

			$config->add( $param->make( "_detailPage", '' ) );
			$config->add( $param->make( "_disableAdd", '0' ) );
			$config->add( $param->make( "_disableErase", '0' ) );
	        $config->add( $param->make( "_disableView", '1' ) );
	        $config->add( $param->make( "_filenameFields", 'title' ) );
	        $config->add( $param->make( "_hideRecordsFromDisabledAccounts", '0' ) );
	        $config->add( $param->make( "_listPage", '' ) );
	        $config->add( $param->make( "_maxRecords", '' ) );
	        $config->add( $param->make( "_maxRecordsPerUser", '' ) );
	        $config->add( $param->make( "_tableName", '' ) );
	        $config->add( $param->make( "listPageFields", 'dragSortOrder, title' ) );
	        $config->add( $param->make( "listPageOrder", 'dragSortOrder DESC' ) );
	        $config->add( $param->make( "listPageSearchFields", '_all_' ) );
	        $config->add( $param->make( "menuName", '' ) );
	        $config->add( $param->make( "menuType", 'multi' ) );
	        $config->add( $param->make( "menuOrder", '999999999999' ) );

		});


		return $this;

	}

	protected function addSystemFields( Section $section )
	{

		$sysFields = $this->getSystemFields();
		$count     = 1;

		foreach( $sysFields as $k => $v )
		{
			$section->addField( $k, $this->getFieldTypes()->system( $k, $v, $count++ ) );
		}

		return $this;

	}

}