<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected $retrievedMessage = 'Data retrieved successfully.';

	protected $createdMessage = 'Data created successfully.';

	protected $updatedMessage = 'Data updated successfully.';

	protected $deletedMessage = 'Data deleted successfully.';

	protected $restoredMessage = 'Data restored successfully.';

	protected $invalidMessage = 'The given data was invalid.';

	protected $unauthorizedMessage = 'This action is unauthorized.';
}
