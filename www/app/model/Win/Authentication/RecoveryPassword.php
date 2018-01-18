<?php

namespace Win\Authentication;

use Win\Mailer\Email;
use Win\Mvc\Application;
use Win\Mvc\Block;

abstract class RecoveryPassword {

	/**
	 * Envia link de recuperacao de senha via Email
	 * @return string | null
	 */
	public static function sendEmail($email) {
		$filters = ['is_enabled = ?' => true, 'access_level > ?' => 0, 'email = ?' => $email];
		$uDAO = new UserDAO();
		$user = $uDAO->fetch($filters);

		if ($user->id > 0) {
			static::updateHash($user);
			$content = new Block('email/content/recovery-password', ['user' => $user]);

			$mail = new Email();
			if (defined('EMAIL_FROM')) {
				$mail->setFrom(EMAIL_FROM, Application::app()->getName());
			}
			$mail->setSubject('Recuperação de Senha');
			$mail->addAddress($user->getEmail(), $user->name);
			$mail->setContent($content);
			return $mail->send();
		} else {
			return 'Este E-mail não está cadastrado no sistema.';
		}
	}

	/** @return string */
	public static function getUrl(User $user) {
		return Application::app()->getBaseUrl() . 'login/alterar-senha/' . $user->recoreryHash . '/';
	}

	/**
	 * Gera/Atualiza um novo recoveryHash
	 * @param User $user
	 * @return string|null
	 */
	public static function updateHash(User $user) {
		$uDAO = new UserDAO();
		$user->recoreryHash = static::generateHash($user);
		return $uDAO->save($user);
	}

	/**
	 * Limpa o recoveryHash
	 * @param User $user
	 * @return string|null
	 */
	public static function clearHash(User $user) {
		$uDAO = new UserDAO();
		$user->recoreryHash = '';
		return $uDAO->save($user);
	}

	/**
	 * Gera um novo recoveryHash
	 * @param User $user
	 */
	public static function generateHash(User $user) {
		return md5($user->getEmail() . date('Y-m-d'));
	}

}
