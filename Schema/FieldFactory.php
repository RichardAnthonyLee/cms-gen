<?php namespace CmsGen\Schema;

use RicAnthonyLee\Itemizer\ItemFactory;
use RicAnthonyLee\Itemizer\ItemCollectionFactory;
use CmsGen\Schema\FieldFormatter;

class FieldFactory{


	public static function make()
	{

		$itemFactory       = new itemFactory;
		$collectionFactory = new ItemCollectionFactory;
		$fields            = new Field;

		return $fields->setItemFactory( $itemFactory )
		              ->setFactory( $collectionFactory )
		              ->setFormatter( new FieldFormatter );

	}


}