<?php
namespace App\Controller;

use \Core\Controller\Controller;

use \Core\Controller\Helpers\MailerService;

use App\Controller\PaginatedQueryAppController;

class BeerController extends Controller
{
    public function __construct() {
        $this->loadModel('beer');
        $this->loadModel('orders_Contents');
    }

    public function home() {
        return $this->render('beer/index');
    }

    public function all() {

        $beers = $this->beer->all();
        
        return $this->render('beer/boutique', [
            'beers' => $beers
        ]);
    }

    /**
     * Ajax Method find
     */
    public function find() {
        if(count($_POST) > 0) {
            if(!$_SESSION['token']) {
                $_SESSION['token'] = substr(md5(uniqid(rand(), true)), 0, 10);
            }
            $beer = $this->beer->find($_POST['id']);
            $line = $this->orders_Contents->getline([$beer->getId(), $_SESSION['token']]);
            if(!$line) {
                $line = [
                    'id_beer' => $beer->getId(),
                    'qty' => 1,
                    'token' => $_SESSION['token']
                ];
                if($this->orders_Contents->create($line)) {
                    echo 'le produit a bien été ajouté au panier';
                }
                else {
                    echo 'Une erreur s\'est produite';
                }
            }
            else {
                if($this->orders_Contents->update($line->getId(), 'id', ['qty' => $line->getQty()+1])) {
                    echo 'le produits a bien été ajouté au panier total('.($line->getQty()+1).')';
                }
                else {
                    echo 'Une erreur s\'est produite';
                }
            }
        }
    }

    public function purchaseOrder() {
        
        if(count($_POST) > 0) {
            foreach($_POST['qty'] as $key => $value) {
                if($value > 0) {
                    $ids[] = $key;
                    $qty[] = $value;
                }
            }
            $ids = implode($ids, ',');

            $beers = $this->beer->getAllInIds($ids);

            $orderTotal = 0;
            foreach($beers as $key => $value) {
                $orderTotal += $value->getPrice() * constant('TVA') * $qty[$key];
            }
            
            return $this->render('beer/confirmationDeCommande', [
                'beers' => $beers,
                'data' => $_POST,
                'qty' => $qty,
                'order' => $orderTotal
            ]);
        }

        $beers = $this->beer->all();

        return $this->render('beer/bondecommande', [
            'beers' => $beers
        ]);
    }

    public function contact() {
        if(count($_POST) > 0) {
            $mailer = new MailerService();
            
            if (
                isset($_POST["send"]) &&
                isset($_POST["from"]) &&
                isset($_POST["object"]) &&
                isset($_POST["message"])
            ) {
                define('MAIL_TO', getenv('GMAIL_USER'));
                define('MAIL_FROM', ''); // valeur par défaut  
                define('MAIL_OBJECT', 'objet du message'); // valeur par défaut  
                define('MAIL_MESSAGE', 'votre message'); // valeur par défaut  
                // drapeau qui aiguille l'affichage du formulaire OU du récapitulatif  
                $mailSent = false;
                // tableau des erreurs de saisie  
                $errors = array();
                // si le courriel fourni est vide OU égale à la valeur par défaut  
                $from = filter_input(INPUT_POST, 'from', FILTER_VALIDATE_EMAIL);
                if ($from === NULL || $from === MAIL_FROM) {
                    $errors[] = 'Vous devez renseigner votre adresse de courrier électronique.';
                    $_SESSION['error'] = 'Vous devez renseigner votre adresse de courrier électronique.';
                } elseif ($from === false) // si le courriel fourni n'est pas valide  
                {
                    $errors[] = 'L\'adresse de courrier électronique n\'est pas valide.';
                    $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_EMAIL);
                }
                $object = filter_input(INPUT_POST, 'object', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW);
                // si l'objet fourni est vide, invalide ou égale à la valeur par défaut  
                if ($object === NULL or $object === false or empty($object) or $object === MAIL_OBJECT) {
                    $errors[] = 'Vous devez renseigner l\'objet.';
                }
                $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
                // si le message fourni est vide ou égal à la valeur par défaut  
                if ($message === NULL or $message === false or empty($message) or $message === MAIL_MESSAGE) {
                    $errors[] = 'Vous devez écrire un message.';
                }
                if (count($errors) === 0) // si il n'y a pas d'erreur  
                {
                    // tentative d'envoi du message  
                    if ($mailer->sendMail(MAIL_TO, $object, $message, false, $from)) {
                        //if( mail( MAIL_TO, $object, $message, "From: $from\nReply-to: $from\n" ) ) 
                        $mailSent = true;
                    } else // échec de l'envoi  
                    {
                        $errors[] = 'Votre message n\'a pas été envoyé.';
                    }
                }
                // si le message a bien été envoyé, on affiche le récapitulatif  
                if ($mailSent === true) {
                    $_SESSION['success'] = 'Votre message a bien été envoyé. Courriel pour la réponse :' . $from . '. Objet : ' . $object . '. Message : ' . nl2br(htmlspecialchars($message));
                } else
                // le formulaire est affiché pour la première fois ou le formulaire a été soumis mais contenait des erreurs  
                {
                    if (count($errors) !== 0) {
                        $_SESSION['error'] = $errors;
                    } else {
                        $_SESSION['error'] = "Tous les champs sont obligatoires...";
                    }
                }
            }
        }
        $title = 'Contact';
        $this->render('beer/contact', [
            'title' => $title
        ]);
    }
}
