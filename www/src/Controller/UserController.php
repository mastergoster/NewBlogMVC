<?php
namespace App\Controller;

use \Core\Controller\Controller;
use \App\Model\Entity\UserEntity;
use \App\Model\Entity\ClientsEntity;

class UserController extends Controller
{
    public function __construct() {
        $this->loadModel('user');
        $this->loadModel('clients');
    }

    public function login(): void 
    {
        $message = false;
        if(count($_POST) > 1) {
            $user = (object) $_POST;
            $password = htmlspecialchars($user->password);
            $user = $this->user->getUser(htmlspecialchars($user->mail), $password);
            if($user) {
                $_SESSION['user'] = $user;
                header('location: /');
                exit();
            }
            else {
                $message = "Votre adresse email ne figure pas dans notre base de donnée";
            }
        }
        $this->profile($message);
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        header('location: /');
        exit();
    }

    public function register() {

        $message ='';//création d'une variable message vide

        if(count($_POST) > 1) { //Si il y a des donnée dans Super Global $_POST
            $user = new UserEntity(); // Création d'une instance de la classe UserEntity
            $user->hydrate($_POST); // Remplissage de l'objet grace à methode Hydrate de /Core/Entity.php
            //dd($user)

            $client = new ClientsEntity();// Création d'une instance de la classe ClientsEntity
            $client->hydrate($_POST);
            //dd($client)

            //Sécurisation du mot de passe à vérifier
            $passwordVerify = htmlspecialchars($_POST['passwordVerify']);
            unset($_POST['passwordVerify']);//Destruction de $_POST['passwordVerify']

            //Si les mots de passes corresondent
            if(password_verify($passwordVerify, $user->getPassword())) {
                //Si les Adresses mails correspondent
                if($user->getMail() == $_POST['mailVerify']) {
                    unset($_POST['mailVerify']);//Destruction $_POST['mailVerify']
                    $user->setIs_active(true); //Remplissage du champs manquant
                    //Insertion en base de donnée grace à methode create de /Core/Table.php
                    $this->user->create($user, true);
                    
                    $client->setId_user($this->user->last());//Remplissage du champs manquant
                    //Insertion en base de donnée grace à methode create de /Core/Table.php
                    $this->clients->create($client, true);

                    $this->profile('Votre inscription a bien été prise en compte');
                    exit();
                }
                else {
                    $message = 'Les deux adresses mail ne correspondent pas';
                }
            }
            else {
                $message = 'Les mots de passe sont différents';
            }
        }

        return $this->render('user/register', [
            'page' => 'Inscription',
            'message' => $message
        ]);
    }

    public function profile($message = null) {
        if(null !== $_SESSION['user'] && $_SESSION['user']) {
            $file = 'profile';
            $page = 'Mon profil';
        }
        else {
            $file = 'login';
            $page = 'Connexion';
        }
        return $this->render('user/'.$file, [
            'page' => $page,
            'message' => $message
        ]);
    }

    public function updateUser() {
        if(count($_POST) > 0) {
            $id = (int) array_pop($_POST);//Stockage de la dernière case de $_POST dans $id
            //Mise à jours bdd grace à methode update de /core/Table.php
            $bool = $this->clients->update($id, 'id_user', $_POST);
            //Mise à jours de la SESSION['user']
            $user = $this->user->getUserByid($id);
            $_SESSION['user'] = $user;

            $this->profile('Votre profil a bien été mis à jour');//Appel de la methode profile de ce controller pour redirection
            exit();
        }
    }

    public function changePassword() {
        if(count($_POST) > 0) {
            $user = $this->user->getUserById(htmlspecialchars($_POST['id']));
            
            //Vérification de l'ancien mot de passe mots de passes
            if(password_verify(htmlspecialchars($_POST['old_password']), $user->getPassword())) {
                //Vérification correspondance des mots de passe
                if(htmlspecialchars($_POST['password']) == htmlspecialchars($_POST['veriftyPassword'])) {
                    //Hashage du password
                    $password = password_hash(htmlspecialchars(htmlspecialchars($_POST['password'])), PASSWORD_BCRYPT);

                    //Mise à jour de la bdd grace à methode update de /core/Table.php
                    if($this->user->update($_POST['id'], 'id', ['password' => $password])) {
                        $message = 'Votre mot de passe a bien été modifié';
                    }
                    else {
                        $message = 'Une erreur s\'est produite';
                    }
                }
                else {
                    $message = 'Les mots de passes ne correspondent pas';
                }
            }
            else {
                $message = 'Mot de passe erroné';
            }
            return $this->profile($message);//Appel de la methode profile de ce controller pour redirection
            exit();
        }
    }
}
