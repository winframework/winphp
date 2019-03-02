<?php

namespace Win\Validation;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
	private $validations = [
		'name' => ['Nome', 'required'],
		'email' => ['E-mail', 'required|email'],
		'age' => ['Idade', 'required|int|min:0|max:120'],
	];

	private $validationsWithMessage = [
		'name' => [
			'Nome',
			'required',
			'messages' => [
				'required' => 'Por favor, preencha o nome.',
			],
		],
		'email' => [
			'E-mail',
			'required|email',
			'messages' => [
				'email' => 'Por favor, informe um e-mail, ex: john@email.com.',
			],
		],
		'age' => [
			'Idade',
			'required|int|min:0|max:120',
			'messages' => [
				'min:0' => 'Por favor, informe uma Idade positiva.',
				'required' => 'Por favor, preencha a Idade.',
			],
		],
	];

	private $defaultData = [
		'name' => 'John',
		'email' => 'john@email.com',
		'age' => 25,
	];

	public function testValidate()
	{
		$data = $this->defaultData;
		$validator = Validator::create($data);
		$data = $validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertFalse($validator->hasError());
		$this->assertCount(3, $data);
	}

	public function testValidateNameIsRequired()
	{
		$data = $this->defaultData;
		$data['name'] = '';
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo Nome é obrigatório.', $error);
	}

	public function testValidateEmailIsRequired()
	{
		$data = $this->defaultData;
		$data['email'] = '';
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo E-mail é obrigatório.', $error);
	}

	public function testValidateEmailIsEmail()
	{
		$data = $this->defaultData;
		$data['email'] = 'emailinvalido';
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo E-mail precisa ser um e-mail válido.', $error);
	}

	public function testValidateAgeIsRequired()
	{
		$data = $this->defaultData;
		$data['age'] = '';
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo Idade é obrigatório.', $error);
	}

	public function testValidateAgeMin()
	{
		$data = $this->defaultData;
		$data['age'] = -1;
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo Idade precisa ser maior do que 0.', $error);
	}

	public function testValidateAgeMax()
	{
		$data = $this->defaultData;
		$data['age'] = 121;
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo Idade precisa ser menor do que 120.', $error);
	}

	public function testValidateMultipleErrors()
	{
		$data = $this->defaultData;
		$data['email'] = 'invalido';
		$data['age'] = '';
		$validator = Validator::create($data);
		$validator->validate($this->validations);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo E-mail precisa ser um e-mail válido.', $error);
	}

	public function testValidateChecked()
	{
		$data = $this->defaultData;
		$data['recaptcha'] = false;

		$validationsReCaptcha = $this->validations;
		$validationsReCaptcha['recaptcha'] = [
			'Não sou um robô',
			'checked',
			'messages' => [
				'checked' => 'Marque a opção "Eu não sou um robô".',
			],
		];

		$validator = Validator::create($data);
		$validator->validate($validationsReCaptcha);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('Marque a opção "Eu não sou um robô".', $error);
	}

	public function testValidateEmailWithMessage()
	{
		$data = $this->defaultData;
		$data['email'] = 'invalido';
		$data['age'] = '';
		$validator = Validator::create($data);
		$validator->validate($this->validationsWithMessage);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('Por favor, informe um e-mail, ex: john@email.com.', $error);
	}

	public function testValidateAgeMinWithMessage()
	{
		$data = $this->defaultData;
		$data['age'] = -1;
		$validator = Validator::create($data);
		$validator->validate($this->validationsWithMessage);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('Por favor, informe uma Idade positiva.', $error);
	}

	/**
	 * Não existe mensagem personalizada para erro de "age max"
	 */
	public function testValidateAgeMaxWithMessage()
	{
		$data = $this->defaultData;
		$data['age'] = 150;
		$validator = Validator::create($data);
		$validator->validate($this->validationsWithMessage);
		$error = $validator->getError();

		$this->assertTrue($validator->hasError());
		$this->assertEquals('O campo Idade precisa ser menor do que 120.', $error);
	}
}
