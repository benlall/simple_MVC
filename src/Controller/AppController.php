<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\CivilityManager;
use Model\ContactManager;



/**
 * Class AppController
 *
 */
class AppController extends AbstractController
{
    public function form(){

        $checked = 0;
        $validFirstname = '';
        $validLastname = '';

        if ($_POST) { // a corriger en faisant les isset de chq $_POST au prealable

            $errors = [];
            $validFirstname = htmlspecialchars($_POST['firstname']);
            $validLastname = htmlspecialchars($_POST['lastname']);

            if (empty($_POST['firstname'])) {
                $errors['firstname'] = 'Vous devez saisir un prénom';
            }
            if (empty($_POST['lastname'])) {
                $errors['lastname'] = 'Vous devez saisir un nom';
            }
            if (empty($_POST['civility'])) {
                $errors['civility'] = 'Vous devez choisir une civilité';
            } else {
                $checked = $_POST['civility'];
            }
            
            if (!$errors) {
                $datas = [];
                //les index de $datas sont les champs de la table
                $datas['firstname'] = $_POST['firstname'];
                $datas['lastname'] = $_POST['lastname'];
                $datas['civility_id'] = $_POST['civility'];
                $date = new \DateTime();
                $datas['creation_date'] = $date->format('Y-m-d H:i:s');

                $contactManager = new ContactManager();
                $contactManager->insert($datas);

                $_SESSION['message'] = 'Insertion OK';
                header('Location:/contact/show');
                die;
            }
        }

        if (isset($_SESSION['message'])) {
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $messageSession = null;
        }

        $templateVariables = ['checked' => $checked,
            'validFirstName' => $validFirstname,
            'validLastName' => $validLastname,
            'messageSession' => $messageSession];

        return $this->twig->render('App/contact.html.twig', $templateVariables);
    }

    public function show()
    {
        $contactManager = new ContactManager();
        $contacts = $contactManager->selectAllDescOrderedBy('creation_date', 3);

        // delete POST
        if (isset($_POST['delete'])) {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                header('Location: /contact/delete/' . $id);
                die;
            }
        }

        if (isset($_SESSION['message'])) {
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $messageSession = null;
        }

        $templateVariables = [
            'contacts' => $contacts,
            'messageSession' => $messageSession ];
        return $this->twig->render('App/showContact.html.twig', $templateVariables);
    }

    public function delete($id)
    {
        $contactManager = new ContactManager();
        $contactManager->delete($id);
        $_SESSION['message'] = 'Suppression OK';
        header ('Location: /contact/show');
        die;
    }

}