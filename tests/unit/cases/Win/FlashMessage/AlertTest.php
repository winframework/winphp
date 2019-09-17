<?php

namespace Win\Notifications;

use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
	public function testInstance()
	{
		$alert = Alert::instance();
		$this->assertInstanceOf(Alert::class, $alert);
	}

	public function testAlertInstance()
	{
		Alert::instance()->add('My first msg');
		Alert::instance('product')->add('My product msg');
		Alert::instance()->add('My second msg', 'success');
		Alert::instance('other')->add('My other msg');

		$this->assertEquals(
			['default' => ['My first msg'], 'success' => ['My second msg']],
			Alert::instance()->all()
		);
		$this->assertEquals(
			['default' => ['My product msg']],
			Alert::instance('product')->all()
		);
		$this->assertEquals(
			['default' => ['My other msg']],
			Alert::instance('other')->all()
		);
	}

	public function testAlertSuccess()
	{
		$alert = Alert::instance();
		$alert->add('My success msg', 'success');
		$this->assertEquals(
			['success' => ['My success msg']],
			$alert->all()
		);
	}

	public function testAlertMany()
	{
		Alert::instance()->add('01');
		Alert::instance()->add('02');
		Alert::instance()->add('03', 'success');
		$this->assertEquals(
			['default' => ['01', '02'], 'success' => ['03']],
			Alert::instance()->all()
		);
	}

	public function testStaticConstructor()
	{
		Alert::alert('Default msg');
		Alert::error('Error msg');
		Alert::info('Info msg');
		Alert::success('Success msg');
		Alert::warning('Warning msg');
		$this->assertEquals(
			['Success msg'],
			Alert::instance()->all()['success']
		);
	}

	public function testAutoClear()
	{
		Alert::error('Error msg');
		Alert::success('Success msg');

		$this->assertEquals(
			['Success msg'],
			Alert::instance()->get('success')
		);
		$this->assertEquals(
			null,
			Alert::instance()->get('success')
		);
		$this->assertEquals(
			['Error msg'],
			Alert::instance()->get('danger')
		);
	}

	public function testHtml()
	{
		Alert::error('Error msg');
		Alert::success('Success msg');
		$result = Alert::instance()->html()->__toString();
		$this->assertContains('alert-success', $result);
	}

	public function testClear()
	{
		Alert::error('Error msg');
		Alert::instance()->clear();
		$this->assertEquals([], Alert::instance()->all());
	}

	public function testHas()
	{
		Alert::error('1');
		$this->assertTrue(Alert::instance()->has());
		Alert::instance()->clear();
		$this->assertFalse(Alert::instance()->has());
	}

	public function testCreateError()
	{
		Alert::instance()->clear();
		Alert::create('Error msg', 'Success');
		$this->assertEquals(
			['danger' => ['Error msg']],
			Alert::instance()->all()
		);
	}

	public function testCreateSuccess()
	{
		Alert::instance()->clear();
		Alert::create(null, 'Success');
		$this->assertEquals(
			['success' => ['Success']],
			Alert::instance()->all()
		);
	}
}
