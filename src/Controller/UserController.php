<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\User;
use Model\UserManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{

    public function signup()
    {
        $validPseudo = '';
        $validEmail = '';
        $passwordError = '';

        if ($_POST) {
            $errors = [];

            if (!empty($_POST['pseudo'])) {
                $pseudo = $_POST['pseudo'];
                $validPseudo = htmlspecialchars($_POST['pseudo']);
            } else {
                $errors['pseudo'] = 'Vous devez saisir un pseudo';
            }
            if (!empty($_POST['email'])) {
                $email = $_POST['email'];
                $validEmail = htmlspecialchars($_POST['email']);
            } else {
                $errors['email'] = 'Vous devez saisir un email valide';
            }
            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $errors['password'] = 'Vous devez saisir un mot de passe';
            }

            if (!$errors && !empty($_POST['passwordConfirm']) && $password == $_POST['passwordConfirm']) {
                $datas = [];
                $datas['pseudo'] = $pseudo;
                $datas['email'] = $email;
                $datas['password'] = password_hash($password, PASSWORD_DEFAULT);

                $userManager = new UserManager();
                $userManager->insert($datas);

                $_SESSION['message'] = 'Inscription OK';
                header('Location: /signin');
                die;
            } else {
                $passwordError = 'les 2 mots de passe ne sont pas identiques';
            }
        }

        if (isset($_SESSION['message'])) {
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $messageSession = null;
        }


        $templateVariables = [
            'validPseudo' => $validPseudo,
            'validEmail' => $validEmail,
            'messageSession' => $messageSession,
            'passwordError' => $passwordError,
        ];

        return $this->twig->render('User/signup.html.twig', $templateVariables);
    }

    public function signin()
    {

        if ($_POST) {
            if (!empty($_POST['email'])) {
                $email = $_POST['email'];
            }

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
            }

            $userManager = new UserManager();
            $user = $userManager->selectOneByFieldName('email', $email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['login'] = true;
                $_SESSION['message'] = 'Vous etes connecté';
                header('location: /');
                die;
            } else {
                $_SESSION['message'] = 'Vous devez d\'abord vous inscrire';
                header('location:/signup');
                die;
            }
        }

        if (isset($_SESSION['login'])) {
            $loginSession = $_SESSION['login'];
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $loginSession = null;
            $messageSession = null;
        }

        $templateVariables = [
            'loginSession' => $loginSession,
            'messageSession' => $messageSession,
        ];

        return $this->twig->render('User/signin.html.twig', $templateVariables);
    }
}
