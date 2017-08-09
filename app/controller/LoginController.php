<?php

namespace controller;

use Win\Alert\AlertError;
use Win\Alert\AlertInfo;
use Win\Alert\AlertSuccess;
use Win\Alert\Session;
use Win\Authentication\RecoveryPassword;
use Win\Authentication\User;
use Win\Authentication\UserDAO;
use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Request\Input;
use Win\Widget\Captcha;

class LoginController extends Controller {

	private $redirectTo = 'login/painel';

	/**
	 * Index
	 */
	public function index() {
		$userDAO = UserDAO::instance();

		if ($userDAO->numRows() > 0) {
			$this->entrar();
		} else {
			$this->iniciarMaster();
		}
	}

	/**
	 * Index
	 */
	public function painel() {
		$this->app->getUser()->requireLogin();
		return new View('login/painel');
	}

	/**
	 * Login (Entrar)
	 */
	private function entrar() {
		$this->preventLogged();
		$this->setTitle('Entrar | ' . $this->app->getName());
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));
			$user->setPassword(Input::post('password'));

			if ($user->login()) {
				$this->app->redirect($this->redirectTo);
			} elseif ($user->isLocked()) {
				new AlertError($user->getLockedMsg());
			} else {
				new AlertError('Email/Senha estão incorretos. Você ainda possui ' . $user->getLoginTriesLeft() . ' tentativas.');
			}
		} else {
			new AlertInfo('Preencha os campos abaixo:');
		}
	}

	/**
	 * Primeiro Admin
	 */
	private function iniciarMaster() {
		$uDAO = new UserDAO();
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));
			$user->setPassword(Input::post('password'));
			$error = $uDAO->insertFirst($user);

			if ($error) {
				new AlertError($error);
			} else {
				$this->entrar();
			}
		} else {
			new AlertError('Não foi encontrado nenhum usuário no sistema. Cadastre seu primeiro usuário abaixo.');
		}

		return new View('login/index');
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
		$this->setTitle('Recuperar Senha | ' . $this->app->getName());
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$error = $this->sendRecoveryEmail($user);
			if (!$error) {
				new AlertSuccess('Foram enviadas instruções para o E-mail: <b>' . $user->getEmail() . '</b>.');
			} else {
				new AlertError($error);
			}
		} else {
			if (Session::hasAlert()) {
				new AlertInfo('Preencha os dados abaixo:');
			}
		}

		return new View('login/recuperar-senha');
	}

	/**
	 * Envia o email de recuperação
	 * @return string | null
	 */
	private function sendRecoveryEmail(User $user) {
		$user->setEmail(Input::post('email'));

		if (!Captcha::isValid()) {
			$error = 'Preencha os caracteres de segurança corretamente.';
		} else {
			$error = RecoveryPassword::sendEmail(Input::post('email'));
		}
		return $error;
	}

	/**
	 * Altera a senha
	 */
	public function alterarSenha() {
		$this->preventLogged();
		$this->setTitle('Alterar Senha | ' . $this->app->getName());
		$recoveryHash = $this->app->getParam(2);

		$uDAO = new UserDAO();
		$user = $uDAO->fetchByRecoveryHash($recoveryHash);
		$this->validateRecoveryHash($user);

		if (!empty(Input::post('submit'))) {
			$user->setPassword(Input::post('new_password1'), Input::post('new_password2'));
			$error = $uDAO->updatePassword($user, null, $recoveryHash);

			if (!$error) {
				$user->login();
				$this->app->redirect($this->redirectTo);
			} else {
				new AlertError($error);
			}
		} else {
			new AlertInfo('Informe sua nova senha:');
		}

		return new View('login/alterar-senha', ['user' => $user]);
	}

	/**
	 * Verifica se recovery está correto
	 * @param User $user
	 */
	private function validateRecoveryHash(User $user) {
		if ($user->getId() == 0) {
			new AlertError('Este link expirou, e será necessário informar seus dados novamente.');
			$this->app->redirect('login/recuperar-senha');
		}
	}

	/**
	 * Evita que usuario esteja logado e acesse informações de não logado
	 */
	private function preventLogged() {
		if ($this->app->getUser()->isLogged()) {
			$this->app->redirect($this->redirectTo);
		}
	}

}
