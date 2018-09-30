<?php

namespace Win\Message;

use PHPUnit_Framework_TestCase;

class AlertTest extends PHPUnit_Framework_TestCase {

	public function testInstance() {
		$alert = new Alert();
		$this->assertInstanceOf(Alert::class, $alert);
	}

	public function testAlert() {
		$alert = new Alert();
		$alert->alert('My first msg');
		$this->assertEquals(['default' => ['My first msg']], $alert->all());
	}

	public function testAlert_Instance() {
		$alert = new Alert();
		$alert->alert('My first msg');
		Alert::instance('product')->alert('My second msg');

		$this->assertEquals(['default' => ['My first msg']], Alert::instance()->all());
		$this->assertEquals(['default' => ['My second msg']], Alert::instance('product')->all());
	}

	public function testAlert_Success() {
		$alert = new Alert();
		$alert->alert('My success msg', 'success');
		$this->assertEquals(['success' => ['My success msg']], $alert->all());
	}

	public function testAlert_Many() {
		Alert::instance()->alert('01');
		Alert::instance()->alert('02');
		Alert::instance()->alert('03', 'success');
		$this->assertEquals(['default' => ['01', '02'], 'success' => ['03']], Alert::instance()->all());
	}

	public function testStaticConstructors() {
		Alert::error('Error msg');
		Alert::info('Info msg');
		Alert::success('Success msg');
		Alert::warning('Warning msg');

		$this->assertEquals(['Success msg'], Alert::instance()->all()['success']);
	}

	public function testAutoClear() {
		Alert::error('Error msg');
		Alert::success('Success msg');

		$this->assertEquals(['Success msg'], Alert::instance()->get('success'));
		$this->assertEquals(null, Alert::instance()->get('success'));
		$this->assertEquals(['Error msg'], Alert::instance()->get('danger'));
	}

	public function testHtml() {
		Alert::error('Error msg');
		Alert::success('Success msg');
		$result = Alert::instance()->html()->toString();
		$this->assertContains('alert-success', $result);
	}

	public function testClear() {
		Alert::error('Error msg');
		Alert::instance()->clear();
		$this->assertEquals([], Alert::instance()->all());
	}

	public function testCreate_Error() {
		Alert::instance()->clear();
		Alert::create('Error msg', 'Success');
		$this->assertEquals(['danger' => ['Error msg']], Alert::instance()->all());
	}

	public function testCreate_Success() {
		Alert::instance()->clear();
		Alert::create(null, 'Success');
		$this->assertEquals(['success' => ['Success']], Alert::instance()->all());
	}

}
