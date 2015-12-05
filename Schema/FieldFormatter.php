<?php namespace CmsGen\Schema;


use RicAnthonyLee\Itemizer\Item;
use RicAnthonyLee\Itemizer\Interfaces\ItemInterface;
use RicAnthonyLee\Itemizer\Interfaces\FormattableItemInterface;
use RicAnthonyLee\Itemizer\Interfaces\FormatterInterface;
use RicAnthonyLee\Itemizer\Interfaces\ItemCollectionInterface;


class FieldFormatter implements FormatterInterface{


	public function format( ItemInterface $item )
	{

		return $this->exportArray( $item );

	}


	/**
	* @return string exported array of item values
	**/


	protected function exportArray( $collection )
	{

		if( !$collection instanceof \Traversable && !is_scalar( $collection->getValue() ) && $collection->getValue() )
		{
			throw new \InvalidArgumentException( "argument 1 must be instance of Traverseable or Scalar value" );
		}

		//convert values into an array

		if( ($collection instanceof \Traversable) )
		{

			foreach( $collection as $key => $item )
			{

				if( !is_scalar( $item->getValue() ) )
				{

					$rr[ $key ] = $this->exportArray( $item );

				}
				else
				{
					$rr[ $key ] = $item->getValue();
				}

			}

		}
		else
		{

			$rr = $collection->getValue();

		}

		return $rr;

	}


}