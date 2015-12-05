<?php namespace CmsGen\Schema;


use RicAnthonyLee\Itemizer\ItemCollection;


class FieldTypes{


	const PRIMARY_KEY = 'num';


	public static function system( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			$field->add( $property->make( 'order', $order ) );
		    $field->add( $property->make( 'type', 'none' ) );
		    $field->add( $property->make( 'label', $label ) );
		    $field->add( $property->make( 'isSystemField', '1' ) );

		};

	}	


	public static function wysiwyg( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$field,
				$property,
				$order
			);

			//add upload properties 
			$field = FieldTypes::addDefaultUploadProperties(  
				$field,
				$property
			);

			$field->add( $property->make( "type",  "wysiwyg" ) );

		};

	}


	public static function image( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			$p = $property;

			$field = FieldTypes::addDefaultProperties(
				$name,
				$label,
				$order,
				$field,
				$p
			);

			$field = FieldTypes::addDefaultUploadProperties(
				$field,
				$p,
				['png', 'jpg', 'jpeg', 'gif'],
				@$settings['width'] ?: 800,
				@$settings['height'] ?: 600
			);

			$field->add( $property->make( "type", "upload" ) );
			$field->add( $property->make( "maxUploads", "1" ) );

		};

	}


	public static function gallery( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			$cb = FieldTypes::image( $name, $label, $order, $settings );
			$cb( $field, $property );

			$field->add( $property->make( "maxUploads", 25 ) );

		};

	}




	public static function upload( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			$p = $property;

			$field = FieldTypes::addDefaultProperties(
				$name,
				$label,
				$order,
				$field,
				$p
			);

			$field = FieldTypes::addDefaultUploadProperties(
				$field,
				$p,
				@$settings['ext'] ?: [],
				@$settings['width'] ?: 800,
				@$settings['height'] ?: 600
			);

			$field->add( $property->make( "type", "upload" ) );

		};

	}


	public static function separator( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order ){

			$p = $property;

			$field = FieldTypes::addDefaultProperties(
				"__separator{$order}__",
				"",
				$order,
				$field,
				$p
			)->setName( $name );

			$field->add( $p->make( "separatorHeader", $label ) );
			$field->add( $p->make( "type", 'separator' ) );
			$field->add( $p->make( "separatorType", 'header bar' ) );
			$field->add( $p->make( "separatorHTML", "<tr><td colspan=\'2\'></td></tr>" ) );

		};

	}


	public static function textarea( $name, $label, $order = 0, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$order,
				$field,
				$property
			);

			$field->add( $p->make( "type", 'textbox' ) );
			$field->add( $p->make( "fieldHeight", @$settings['height'] ?: '300' ) );
			$field->add( $p->make( "autoFormat", '1' ) );

		};

	}


	public static function text( $name, $label, $order, array $settings = array() )
	{

		return function( $field, $property ) use ( $name, $label, $order ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$field,
				$property,
				$order
			);

			$field->add( $p->make( "type", 'textfield' ) );
			$field->add( $p->make( "charsetRule", '' ) );
			$field->add( $p->make( "charset", '' ) );

		};

	}


	public static function hasOne( $name, $label, $order, array $settings = array() )
	{

		if( !isset( $settings['relation']['table'] ) || !isset( $settings['relation']['field'] ) )
		{
			throw new \InvalidArgumentException( "fieldType::hasOne expects settings array to contain 'relation' key, with field property " );
		}

		return function( $field, $property ) use ( $name, $label, $order ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$field,
				$property,
				$order
			);

			$field->add( $p->make( "type", 'list' ) );
			$field->add( $p->make( "listType", 'pulldown' ) );
			$field->add( $p->make( "optionsType", 'table' ) );
			$field->add( $p->make( "optionsTablename", $settings['relation']['table'] ) );
			$field->add( $p->make( "optionsValueField", self::PRIMARY_KEY ) );
			$field->add( $p->make( "optionsLabelField", $settings['relation']['field'] ) );

		};

	}

	public static function hasMany( $name, $label, $order, array $settings = array() )
	{

		if( !isset( $settings['relation']['table'] ) || !isset( $settings['relation']['field'] ) )
		{
			throw new \InvalidArgumentException( "fieldType::hasMany expects settings array to contain 'relation' key, with field property " );
		}

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			//envoke hasOne callback to give field 
			//default related table list properties
			$cb = FieldTypes::hasOne( 
				$name, 
				$label, 
				$order, 
				$settings
			);

			$cb( $field, $property );

			//alter list type to become a multi-pulldown

			$field->add( $p->make( "listType", "pulldownMulti" ) );


		};
	}


	public static function date( $name, $label, $order = 0, $field, $property )
	{


		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$order,
				$field,
				$property
			);

			//alter list type to become a multi-pulldown

			$field->add( $p->make( "indexed", "0" ) );
			$field->add( $p->make( "defaultDate", "" ) );
			$field->add( $p->make( "defaultDateString", "2015-01-01 00:00:00" ) );
			$field->add( $p->make( "showTime", "0" ) );
			$field->add( $p->make( "showSeconds", "0" ) );
			$field->add( $p->make( "use24HourFormat", "0" ) );
			$field->add( $p->make( "yearRangeStart", "" ) );
			$field->add( $p->make( "yearRangeEnd", "" ) );


		};

	}

	public static function checkbox( $name, $label, $field, $property, $order = 0 )
	{

		return function( $field, $property ) use ( $name, $label, $order, $settings ){

			//add default properties to field
			$field = FieldTypes::addDefaultProperties( 
				$name, 
				$label,
				$order,
				$field,
				$property
			);

			//alter list type to become a multi-pulldown

			$field->add( $p->make( "type", "checkbox" ) );
			$field->add( $p->make( "checkedValue", '1' ) );
			$field->add( $p->make( "uncheckedValue", '0' ) );
			$field->add( $p->make( "checkedByDefault", @$settings['checked'] ? '1' : '0' ) );

		};

	}


	public static function addDefaultProperties( $name, $label, $order = 0, $field = null, $property = null )
	{

		$field->setName( $name );
        $field->setAlias( $name );
        $field->add( $property->make( "order", $order, "order" ) );
        $field->add( $property->make( "label", $label ) );
	    $field->add( $property->make( "fieldPrefix", "" ) );
	    $field->add( $property->make( "description", "" ) );
	    $field->add( $property->make( "defaultContent", "" ) );
	    $field->add( $property->make( "allowUploads", "1" ) );
	    $field->add( $property->make( "isRequired", "0" ) );
	    $field->add( $property->make( "isUnique", "0" ) );
	    $field->add( $property->make( "minLength", "" ) );
	    $field->add( $property->make( "maxLength", "" ) );
	    $field->add( $property->make( "fieldHeight", "" ) );

	    return $field;

	}

	public static function addDefaultUploadProperties( $field, $property, $ext = array(), $width =  800, $height = 600 )
	{

		$p = $property;

		$ext = !empty( $ext ) ? $ext : self::getDefaultUploadExts();

        $field->add( $p->make( 'allowedExtensions', implode( ",", $ext )  ) );
        $field->add( $p->make( 'checkMaxUploadSize', '0' ) );
        $field->add( $p->make( 'maxUploadSizeKB', '5120' ) );
        $field->add( $p->make( 'checkMaxUploads', '1' ) );
        $field->add( $p->make( 'maxUploads', '25' ) );
        $field->add( $p->make( 'resizeOversizedImages', '1' ) );
        $field->add( $p->make( 'maxImageHeight', (string) $height  ) );
        $field->add( $p->make( 'maxImageWidth', (string) $width ) );
        $field->add( $p->make( 'createThumbnails', '1' ) );
        $field->add( $p->make( 'createThumbnails2', '1' ) );
        $field->add( $p->make( 'createThumbnails3', '1' ) );
        $field->add( $p->make( 'createThumbnails4', '1' ) );
        $field->add( $p->make( 'maxThumbnailHeight', (string) $height ) );
        $field->add( $p->make( 'maxThumbnailWidth', (string) $width ) );
        $field->add( $p->make( 'maxThumbnailHeight2', (string) floor( $height*.5 ) ) );
        $field->add( $p->make( 'maxThumbnailWidth2', (string) floor( $width*.5 ) ) );
        $field->add( $p->make( 'maxThumbnailHeight3', (string) floor( $height*.5 ) ) );
        $field->add( $p->make( 'maxThumbnailWidth3', (string) floor( $width*.5 ) ) );
        $field->add( $p->make( 'maxThumbnailHeight4', (string) floor( $height*.35 ) ) );
        $field->add( $p->make( 'maxThumbnailWidth4', (string) floor( $width*.35 ) ) );
        $field->add( $p->make( 'useCustomUploadDir', '0' ) );
        $field->add( $p->make( 'customUploadDir', '' ) );
        $field->add( $p->make( 'customUploadUrl', '' ) );
        $field->add( $p->make( 'infoField1', 'Title' ) );
        $field->add( $p->make( 'infoField2', 'Caption' ) );

		return $field;       



	}


	public static function getDefaultUploadExts()
	{

		return ['gif','jpg','png','wmv','mov','swf','pdf'];

	}



}
