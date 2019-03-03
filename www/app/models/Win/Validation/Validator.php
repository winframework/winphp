<?php

namespace Win\Validation;

/**
 * Validador automatizado
 */
class Validator
{
	/**
	 * Dados a serem validados
	 * @var array
	 */
	private $data = [];

	/**
	 * Regras de validação
	 * @var array
	 */
	private $validations = [];

	/**
	 * Erros retornados durante a validação
	 * @var array
	 */
	private $errors = [];

	const INDEX_NAME = 0;
	const INDEX_RULES = 1;
	const INDEX_MESSAGES = 'messages';

	/**
	 * Cria um validador
	 * @param array $validations
	 * @return static
	 */
	public static function create($validations)
	{
		return new static($validations);
	}

	/**
	 * Cria um validador
	 * @param array $validations
	 * @return static
	 */
	public function __construct($validations)
	{
		$this->validations = $validations;
	}

	/**
	 * Retorna nome da validação
	 * @param array $validation
	 * @return string
	 */
	protected function getName($validation)
	{
		return $validation[self::INDEX_NAME];
	}

	/**
	 * Retorna as regras da validação
	 * @param array $validation
	 * @return string[]
	 */
	protected function getRules($validation)
	{
		if (key_exists(self::INDEX_RULES, $validation)) {
			return explode('|', $validation[self::INDEX_RULES]);
		}

		return [];
	}

	/**
	 * Retorna a mensagem personalizada desta validação
	 * @param string $rule
	 * @param array $validation
	 * @return string|null
	 */
	protected function getMessage($rule, $validation)
	{
		if (key_exists(self::INDEX_MESSAGES, $validation)) {
			$messages = $validation[self::INDEX_MESSAGES];
			if (key_exists($rule, $messages)) {
				return $messages[$rule];
			}
		}

		return null;
	}

	/**
	 * Valida e retorna os dados válidos
	 * @param array $data
	 * @return array
	 */
	public function validate($data)
	{
		$this->data = $data;
		foreach ($this->validations as $index => $validation) {
			$name = $this->getName($validation);
			$rules = $this->getRules($validation);

			foreach ($rules as $rule) {
				$message = $this->getMessage($rule, $validation);
				if (!$this->isFieldValid($index, $rule, $name, $message)) {
					break;
				}
			}
		}

		return $this->data;
	}

	/**
	 * Retorna TRUE se o campo é valido
	 * @param string $index
	 * @param string $rule
	 * @param string $name
	 * @param string|null $message
	 * @return bool
	 */
	private function isFieldValid($index, $rule, $name, &$message = null)
	{
		if (key_exists($index, $this->data)) {
			if (!Rules::isValid($this->data[$index], $rule)) {
				if (!$message) {
					$message = Rules::getError();
				}
				$this->errors[] = str_replace(':name', $name, $message);
			}
		}

		return !$this->hasError();
	}

	/**
	 * Retorna TRUE se tem algum erro
	 * @return bool
	 */
	public function hasError()
	{
		return count($this->errors) > 0;
	}

	/**
	 * Retorna a mensagem de erro
	 * @return string|null
	 */
	public function getError()
	{
		if (count($this->errors) > 0) {
			return $this->errors[0];
		}

		return null;
	}
}
