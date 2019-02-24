<?php

namespace Win\Database\Orm\Traits;

trait ModelMapperTrait
{
	/** @var Model */
	protected $model;

	/** @return Model */
	public function getModel()
	{
		return $this->model;
	}

	/** @return bool */
	public function modelExists()
	{
		return $this->model->getId() > 0;
	}
}
