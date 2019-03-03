<?php

namespace Win\Validation;

/**
 * Regras de validação
 *
 * Cada regra é um método publico
 */
class Rules
{
	/**
	 * Armazena o erro que ocorreu durante a validação
	 * @var string|null
	 */
	protected static $error;

	/**
	 * Retorna TRUE se o valor está dentro da regra
	 * @param mixed $value
	 * @param string $rule
	 * @return bool
	 */
	public static function isValid($value, $rule)
	{
		static::$error = null;
		$ruleParam = explode(':', $rule);
		$ruleName = array_shift($ruleParam);
		$method = Rules::class . '::' . $ruleName;
		array_unshift($ruleParam, $value);
		call_user_func_array($method, $ruleParam);

		return is_null(static::$error);
	}

	/**
	 * Valida se o campo foi preenchido
	 * @param string $value
	 */
	protected static function required($value)
	{
		if (strlen($value) < 1) {
			static::setError('O campo :name é obrigatório.');
		}
	}

	/**
	 * Valida se é um email
	 * @param string $email
	 */
	protected static function email($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			static::setError('O campo :name precisa ser um e-mail válido.');
		}
	}

	/**
	 * Valida se é um inteiro
	 * @param mixed $value
	 */
	protected static function int($value)
	{
		if (!is_int($value)) {
			static::setError('O campo :name precisa ser um número.');
		}
	}

	/**
	 * Valida se é o valor mínimo esperado
	 * @param mixed $value
	 * @param int $min
	 */
	protected static function min($value, $min)
	{
		if ($value < $min) {
			static::setError(
				'O campo :name precisa ser maior do que ' . $min . '.'
			);
		}
	}

	/**
	 * Valida se é o valor máximo esperado
	 * @param mixed $value
	 * @param int $max
	 */
	protected static function max($value, $max)
	{
		if ($value > $max) {
			static::setError(
				'O campo :name precisa ser menor do que ' . $max . '.'
			);
		}
	}

	/**
	 * Valida se a opção foi marcada
	 * @param bool $value
	 */
	protected static function checked($value)
	{
		if (false === (bool) $value) {
			static::setError('Marque a opção ":name".');
		}
	}

	/**
	 * Retorna o Erro obtido durante a validação
	 * @return string
	 */
	public static function getError()
	{
		return static::$error;
	}

	/**
	 * Atribui o erro
	 * @param string $error
	 */
	protected static function setError($error)
	{
		static::$error = $error;
	}
}
