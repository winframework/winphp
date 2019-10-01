<?php

namespace Win\Models\Validation;

use Exception;

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

		if (method_exists(__CLASS__, $ruleName)) {
			call_user_func_array($method, $ruleParam);
		} else {
			throw new Exception('The validation "' . $rule . '" do NOT exists.');
		}

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
	 * Valida se o valor é o mínimo esperado
	 *
	 * Inteiro: valor mínimo
	 * String: tamanho mínimo da string
	 * @param mixed $value
	 * @param int $min
	 */
	protected static function min($value, $min)
	{
		if (is_int($value) && $value < $min) {
			static::setError(
				'O campo :name precisa ser maior do que $1.'
			);
		}
		if (is_string($value) && strlen($value) < $min) {
			static::setError(
				'O campo :name deve ter pelo menos $1 caracteres.'
			);
		}
	}

	/**
	 * Valida se é o valor máximo esperado
	 *
	 * Inteiro: valor máximo
	 * String: tamanho máximo da string
	 * @param mixed $value
	 * @param int $max
	 */
	protected static function max($value, $max)
	{
		if (is_int($value) && $value > $max) {
			static::setError(
				'O campo :name precisa ser menor do que $1.'
			);
		}
		if (is_string($value) && strlen($value) > $max) {
			static::setError(
				'O campo :name deve ter no máximo $1 caracteres.'
			);
		}
	}

	/**
	 * Valida se a string é igual a outra
	 * @param mixed $value
	 * @param mixed $compare
	 */
	protected static function equal($value, $compare)
	{
		if ($value != $compare) {
			static::setError(
				'O campo :name deve ser informado duas vezes.'
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
	 * Retorna a mensagem de erro obtida durante a validação
	 * @param array $find
	 * @param array $replace
	 * @param string $custom
	 * @return string
	 */
	public static function getError($find, $replace, $custom = null)
	{
		return str_replace($find, $replace, $custom ?: static::$error);
	}

	/**
	 * Atribui a mensagem de erro
	 * @param string $error
	 */
	protected static function setError($error)
	{
		static::$error = $error;
	}
}
