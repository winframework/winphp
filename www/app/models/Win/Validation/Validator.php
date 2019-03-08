<?php

namespace Win\Validation;

use Win\Formats\Arr\Data;

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
	 * @var Data
	 */
	private $validations;

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
		$this->validations = Data::instance();
		$this->validations->load($validations);
	}

	/**
	 * Retorna o nome da validação
	 * @param string $index
	 * @return string
	 */
	protected function getName($index)
	{
		return (string) $this->validations->get($index . '.' . self::INDEX_NAME);
	}

	/**
	 * Retorna o dado desejado
	 * @param string $index
	 * @return mixed
	 */
	public function getData($index = null)
	{
		if (is_null($index)) {
			return $this->data;
		}
		if (key_exists($index, $this->data)) {
			return $this->data[$index];
		}

		return null;
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
	 * Retorna a mensagem final desta validação
	 * @param string $index
	 * @param string $rule
	 * @return string|null
	 */
	protected function getMessage($index, $rule)
	{
		$name = $this->getName($index);
		$key = $index . '.' . self::INDEX_MESSAGES . '.' . $rule;
		$message = (string) $this->validations->get($key);

		return Rules::getError($name, $message);
	}

	/**
	 * Valida os dados
	 * @param array $data
	 * @return bool
	 */
	public function validate($data)
	{
		$this->data = $data;
		foreach ($this->validations->all() as $index => $validation) {
			foreach ($this->getRules($validation) as $rule) {
				$this->isFieldValid($index, $rule);
			}
		}

		return !$this->hasError();
	}

	/**
	 * Retorna TRUE se o campo é valido
	 * @param string $index
	 * @param string $rule
	 * @return bool
	 */
	private function isFieldValid($index, $rule)
	{
		$value = $this->getData($index);

		if (!Rules::isValid($value, $rule)) {
			$this->errors[] = $this->getMessage($index, $rule);
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
