<?php

namespace controller;

use Win\Request\Input;
use Win\Authentication\UserDAO;
use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Widget\Captcha;
use Win\Alert\AlertDefault;
use Win\Alert\AlertError;
use Win\Alert\AlertSuccess;

class LoginController extends Controller {

	private $redirectTo = 'index';

	/**
	 * Index
	 */
	public function index() {
		$uDAO = new UserDAO();
		if ($uDAO->totalUsers() > 0) {
			$this->entrar();
		} else {
			$this->iniciarMaster();
		}
	}

	/**
	 * Login (Entrar)
	 */
	private function entrar() {
		$this->preventLogged();
		$this->app->setTitle('Entrar');
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));
			$user->setPassword(Input::post('password'));

			if ($user->login()) {
				$this->app->redirect($this->redirectTo);
			} else {
				new AlertError('Email/Senha estão incorretos.');
			}
		} else {
			new AlertDefault('Preencha os campos abaixo:');
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
		$this->app->setTitle('Recuperar Senha');
		$user = $this->app->getUser();

		if (!empty(Input::post('submit'))) {
			$user->setEmail(Input::post('email'));

			if (!Captcha::isCorrect()) {
				new AlertError('Preencha os caracteres de segurança corretamente.');
			} elseif (!$user->sendRecoveryHash()) {
				new AlertError('Este E-mail não está cadastrado no sistema.');
			} else {
				new AlertSuccess('Foram enviadas instruções para o E-mail: <b>' . $user->getEmail() . '</b>.');
			}
		} else {
			new AlertDefault('Preencha os dados abaixo:');
		}

		return new View('login/recuperar-senha');
	}

	/**
	 * Altera a senha
	 */
	public function alterarSenha() {
		$this->preventLogged();
		$this->app->setTitle('Alterar Senha');

		$uDAO = new UserDAO();
		$user = $uDAO->fetchByRecoveryHash($this->app->getParam(2));

		if ($user->getId() == 0) {
			new AlertError('Este link expirou, e será necessário informar seus dados novamente.');
			$this->app->redirect('login/recuperar-senha');
		}

		if (!empty(Input::post('submit'))) {
			$error = $uDAO->updatePassword($user, Input::post('new_password1'), Input::post('new_password2'));

			if (!$error) {
				$user->login();
				$this->app->redirect($this->redirectTo);
			} else {
				new AlertError($error);
			}
		} else {
			new AlertDefault('Informe sua nova senha:');
		}

		return new View('login/alterar-senha', ['user' => $user]);
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
