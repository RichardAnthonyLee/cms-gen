<?php namespace CmsGen\Schema;


class SectionFactory{


	public function make()
	{

		$section = new Section;

		$section->setConfig( $this->getConfigFactory()->make() )
		        ->setFields( $this->getFieldFactory()->make() );

		return $this->getConfigurator()->configure( $section );        

	}

	public function setFieldFactory( FieldFactory $factory )
	{

		$this->fieldFactory = $factory;
		return $this;

	}

	public function getFieldFactory()
	{

		return $this->fieldFactory;

	}

	public function setConfigFactory( ConfigFactory $factory )
	{

		$this->configFactory = $factory;
		return $this;

	}


	public function getConfigFactory()
	{

		return $this->configFactory;

	}


	public function setConfigurator( SectionConfigurator $config )
	{

		$this->config = $config;
		return $this;

	}


	public function getConfigurator()
	{

		return $this->config;

	}


}