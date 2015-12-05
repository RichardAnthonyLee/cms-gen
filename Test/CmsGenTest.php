<?php

use CmsGen\Schema\FieldFactory;
use CmsGen\Schema\ConfigFactory;
use CmsGen\Schema\SectionFactory;
use CmsGen\Schema\SectionConfigurator;
use CmsGen\Schema\FieldTypes;
use CmsGen\Schema\FieldRegistrar;

require dirname(dirname(__FILE__)) .'/vendor/autoload.php';


class CmsGenTest extends PHPUnit_Framework_TestCase{


	public function testInit()
	{

		$fields  = new FieldFactory;
		$conf    = new ConfigFactory;
		$sConf   = new SectionConfigurator;
		$types   = new FieldTypes;

		$sConf->setFieldTypes( $types );

		$section = new SectionFactory;

		$section->setFieldFactory( $fields )
		        ->setConfigFactory( $conf )
		        ->setConfigurator( $sConf );

		$sect    = $section->make();

		return $sect;	         

	}

	/**
	* @depends testInit
	**/

	public function testDisplay( $sect )
	{

		$reg = new FieldRegistrar;

		$reg->setSection( $sect );

		$reg->setFieldTypes( new FieldTypes );


		$reg->register( 
			"image", 
			"profile_pic", 
			"Profile Picture", 
			$sect['fields']->count()+1, 
			[ 'height' => 350, 'width' => '350' ] );

		$reg->register(
			"gallery",
			"slider",
			"Homepage Slider",
			$sect['fields']->count()+1,
			[ 'height' => 500, 'width' => 900, 'maxUploads' => 500 ]
		);

		$this->assertEquals( $sect['fields']['slider']['order']->getValue(), 8 );


		//echo var_export( $sect->format() );



		/*

		foreach( $sect['config']['settings'] as $setting )
		{

			echo "\n".$setting->getName() ."=" .$setting->getValue();

		}
		*/

	}

}