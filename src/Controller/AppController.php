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
    public function form()
    {
        $civility = 0;
        $checked = 'checked';
        $validFirstname = '';
        $validLastname = '';
        $errorFirstname = '';
        $errorLastname = '';
        $errorCivility = '';

        if ($_POST) {
            if (!empty($_POST['firstname'])) {
                $firstname = $_POST['firstname'];
                $validFirstname = htmlspecialchars($_POST['firstname']);
            } else {
                $errorFirstname = 'Vous devez saisir un prénom';
            }
            if (!empty($_POST['lastname'])) {
                $lastname = $_POST['lastname'];
                $validLastname = htmlspecialchars($_POST['lastname']);
            } else {
                $errorLastname = 'Vous devez saisir un nom';
            }
            if (!empty($_POST['civility'])) {
                $civility = $_POST['civility'];
            } else {
                $errorCivility = 'Vous devez choisir une civilité';
            }
            
            if (!$errorFirstname && !$errorLastname && !$errorCivility) {
                $datas = [];
                //les index de $datas sont les champs de la table
                $datas['firstname'] = $firstname;
                $datas['lastname'] = $lastname;
                $datas['civility_id'] = $civility;
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

        $templateVariables = ['civility' => $civility,
            'checked' => $checked,
            'validFirstName' => $validFirstname,
            'validLastName' => $validLastname,
            'messageSession' => $messageSession,
            'errorFirstname' => $errorFirstname,
            'errorLastname' => $errorLastname,
            'errorCivility' => $errorCivility,
        ];

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

        // update POST
        if (isset($_POST['modify'])) {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                header('Location: /contact/update/' . $id);
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
        header('Location: /contact/show');
        die;
    }

    public function update($id)
    {

        $contactManager = new ContactManager();
        $contact = $contactManager->selectOneById($id);

        if (isset($_POST['update'])) {
            $datas = [];
            $datas['firstname'] = $_POST['firstname'];
            $datas['lastname'] = $_POST['lastname'];
            $id = $_POST['id'];

            $contactManager = new ContactManager();
            $contactManager->update($id, $datas);

            $_SESSION['message'] = 'Modification OK';
            header('Location:/contact/show');
            die;
        }

        if (isset($_SESSION['message'])) {
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $messageSession = null;
        }

        $templateVariables = [
            'messageSession' => $messageSession,
            'id' => $id,
            'contact' => $contact];

        return $this->twig->render('App/contact_update.html.twig', $templateVariables);
    }
}
