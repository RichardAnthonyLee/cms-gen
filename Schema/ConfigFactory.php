<?php namespace CmsGen\Schema;

use RicAnthonyLee\Itemizer\ItemFactory;
use RicAnthonyLee\Itemizer\ItemCollectionFactory;
use CmsGen\Schema\FieldFormatter;


class ConfigFactory{


	public static function make()
	{

		$itemFactory       = new itemFactory;
		$collectionFactory = new ItemCollectionFactory;
		$config            = new SectionConfig;
		$formatter         = new FieldFormatter;

		$config->setItemFactory( $itemFactory )
		       ->setFactory( $collectionFactory );

		$config->add( "settings", function( $settings, $param ) use ( $formatter ) {

			$settings->setFormatter( $formatter );

		} );

		return $config;

	}


}