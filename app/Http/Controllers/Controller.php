<?php

namespace App\Http\Controllers;

use Datakrama\Lapires\Traits\ApiResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use AuthorizesRequests;
	use DispatchesJobs;
	use ValidatesRequests;
	use ApiResponser;

	/**
	 * Data created response
	 *
	 * @param array $data
	 * @return void
	 */
	public function dataCreated($data)
	{
		return $this->successResponse($data, __('Data created successfully.'), 201);
	}

	/**
	 * Data updated response
	 *
	 * @param array $data
	 * @return void
	 */
	public function dataUpdated($data)
	{
		return $this->successResponse($data, __('Data updated successfully.'));
	}

	/**
	 * Data deleted response
	 *
	 * @return void
	 */
	public function dataDeleted()
	{
		return $this->successResponse(null, __('Data deleted successfully.'));
	}

	/**
	 * Data updated response
	 *
	 * @param array $data
	 * @return void
	 */
	public function dataRestored($data)
	{
		return $this->successResponse($data, __('Data restored successfully.'));
	}
}
