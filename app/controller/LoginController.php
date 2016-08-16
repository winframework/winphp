<?php

namespace controller;

use Win\Request\Input;
use Win\Authentication\UserDAO;
use Win\Mvc\Controller;
use Win\Mvc\View;

class LoginController extends Controller {

	private $redirecTo = 'login/painel';

	/**
	 * Index (Login)
	 */
	public function index() {
		$this->preventLogged();
		$this->app->setTitle('Entrar');
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));
			$user->setPassword(Input::post('password'));

			if ($user->login()) {
				$this->app->redirect($this->redirecTo);
			} else {
				$this->addData('error', 'Email/Senha estão incorretos.');
			}
		}
	}

	/**
	 * Painel (Já está Logado)
	 */
	public function painel() {
		$this->app->getUser()->requireLogin();
		$this->app->setTitle('Você está logado');
	}

	/**
	 * Logout (Sair)
	 */
	public function sair() {
		$this->app->getUser()->logout();
		$this->app->redirect('login');
	}

	/**
	 * Envia email recuperacao
	 */
	public function recuperarSenha() {
		$this->preventLogged();
		$this->app->setTitle('Recuperar Senha');
		$user = $this->app->getUser();

		if ($this->app->getParam(2) == 'invalido') {
			$this->addData('error', 'Este link expirou, preencha novamente os dados abaixo.');
		}

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));

			/* Captcha */
			$captcha = strtolower(Input::post('captcha'));
			$sessionCaptcha = strtolower(filter_var($_SESSION['captcha']));
			unset($_SESSION['captcha']);

			if ($captcha != $sessionCaptcha) {
				$this->addData('error', 'Preencha os caracteres de segurança corretamente.');
			} elseif (!$user->sendRecoveryHash()) {
				$this->addData('error', 'Este E-mail não está cadastrado no sistema.');
			} else {
				$this->addData('success', 'Foram enviadas instruções para o E-mail: <b>' . $user->getEmail() . '</b>.');
			}
		}

		return new View('login/recuperar-senha');
	}

	/**
	 * Altera a senha
	 */
	public function alterarSenha() {
		$this->preventLogged();
		$this->app->setTitle('Alterar Senha');

		$recoveryHash = $this->app->getParam(2);
		$newPassword1 = Input::post('new_password1');
		$newPassword2 = Input::post('new_password2');

		$uDAO = new UserDAO();
		$user = $uDAO->fetchByField('recovery_hash', $recoveryHash);

		if ($user->getId() == 0) {
			$this->app->redirect('login/recuperar-senha/invalido');
		}

		if (!empty(Input::post('submit'))) {
			$error = $uDAO->updatePassword($user, $newPassword1, $newPassword2);

			if (!$error) {
				$user->login();
				$this->app->redirect($this->redirecTo);
			} else {
				$this->addData('error', $error);
			}
		}

		return new View('login/alterar-senha', ['user' => $user]);
	}

	/**
	 * Evita que usuario esteja logado e acesse informações de não logado
	 */
	private function preventLogged() {
		if ($this->app->getUser()->isLogged()) {
			$this->app->redirect($this->redirecTo);
		}
	}

}
