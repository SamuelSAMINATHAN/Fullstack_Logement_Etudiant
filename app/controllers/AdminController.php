<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AdminController extends Controller
{
    private $adminModel;
    private $utilisateurModel;
    private $bailleurModel;
    private $annonceModel;
    private $signalementModel;
    private $infoModel;
    private $contactModel;

    public function __construct()
    {
        $this->adminModel = $this->model('AdminModel');
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->bailleurModel = $this->model('BailleurModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->signalementModel = $this->model('SignalementModel');
        $this->infoModel = $this->model('InformationLegaleModel');
        $this->contactModel = $this->model('ContactModel');
    }

    /**
     * Affiche la page d'accueil du backoffice admin
     */
    public function home()
    {
        $this->redirect('/admin/dashboard');
    }

    /**
     * Affiche la page d'accueil du backoffice admin
     */
    public function dashboard()
    {
        $this->requireAdmin();

        $data = [
            'stats' => [
                'users_count' => count($this->utilisateurModel->getAllUsersWithDetails()),
                'annonces_count' => count($this->annonceModel->getAllAnnouncements()),
                'signalements_count' => $this->signalementModel->countPendingReports(),
            ],
            'recent_users' => $this->utilisateurModel->getAllUsersWithDetails()
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Gestion des utilisateurs
     */
    public function users()
    {
        $this->requireAdmin();
        $users = $this->utilisateurModel->getAllUsersWithDetails();
        $this->view('admin/users/index', ['users' => $users]);
    }

    /**
     * Vérification des bailleurs
     */
    public function verifyBailleurs()
    {
        $this->requireAdmin();
        $bailleurs = $this->utilisateurModel->getBailleursToVerify();
        $this->view('admin/users/verify_bailleurs', ['bailleurs' => $bailleurs]);
    }

    /**
     * Action : Vérifier un bailleur
     */
    public function verify($id)
    {
        $this->requireAdmin();
        if ($this->bailleurModel->verifyLandlord($id)) {
            Session::setFlash('success', 'Bailleur vérifié avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la vérification.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Shadowban un bailleur
     */
    public function shadowban($id)
    {
        $this->requireAdmin();
        $status = isset($_GET['status']) ? (int)$_GET['status'] : 1;
        if ($this->bailleurModel->toggleShadowban($id, $status)) {
            $msg = $status ? 'Bailleur shadowbanni.' : 'Shadowban retiré.';
            Session::setFlash('success', $msg);
        } else {
            Session::setFlash('error', 'Erreur lors de l\'opération.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Changer le rôle
     */
    public function changeRole($id)
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }

        $newRole = $_POST['role'] ?? '';
        if (in_array($newRole, ['etudiant', 'bailleur'])) {
            if ($this->utilisateurModel->updateUser($id, ['role' => $newRole])) {
                Session::setFlash('success', 'Rôle mis à jour.');
            } else {
                Session::setFlash('error', 'Erreur lors de la mise à jour.');
            }
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Supprimer un utilisateur
     */
    public function deleteUser($id = null)
    {
        $this->requireAdmin();
        if ($id === null) {
            $this->redirect('/admin/users');
        }

        if ($this->utilisateurModel->deleteUser($id)) {
            Session::setFlash('success', 'Utilisateur supprimé.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Gestion du contenu légal
     */
    public function legal()
    {
        $this->requireAdmin();
        $infos = $this->infoModel->getAllInformation() ?: [];
        $this->view('admin/content/legal', ['infos' => $infos]);
    }

    /**
     * Ajout d'un contenu légal
     */
    public function addLegal()
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $data = [
                'titre' => $_POST['titre'] ?? '',
                'contenu' => $_POST['contenu'] ?? '',
                'dateMiseAJour' => date('Y-m-d H:i:s')
            ];

            if (empty($data['titre']) || empty($data['contenu'])) {
                Session::setFlash('error', 'Le titre et le contenu sont obligatoires.');
            } else {
                if ($this->infoModel->createInformation($data)) {
                    Session::setFlash('success', 'Nouveau texte légal ajouté.');
                    $this->redirect('/admin/legal');
                } else {
                    Session::setFlash('error', 'Erreur lors de l\'ajout.');
                }
            }
        }
        $this->view('admin/content/legal_form', ['info' => null]);
    }

    /**
     * Édition du contenu légal
     */
    public function editLegal($id)
    {
        $this->requireAdmin();
        $info = $this->infoModel->getInformationById($id);
        if (!$info) {
            Session::setFlash('error', 'Texte introuvable.');
            $this->redirect('/admin/legal');
        }

        if ($this->isPost()) {
            $data = [
                'titre' => $_POST['titre'] ?? '',
                'contenu' => $_POST['contenu'] ?? '',
                'dateMiseAJour' => date('Y-m-d H:i:s')
            ];

            if ($this->infoModel->updateInformation($id, $data)) {
                Session::setFlash('success', 'Information légale mise à jour.');
                $this->redirect('/admin/legal');
            }
        }
        $this->view('admin/content/legal_form', ['info' => $info]);
    }

    /**
     * Formulaire de création d'un admin
     */
    public function createAdmin()
    {
        $this->requireAdmin();
        $this->view('admin/users/create_admin');
    }

/**
     * Liste tous les administrateurs du site
     */
    public function admins()
    {
        $this->requireAdmin();
        $admins = $this->adminModel->getAllAdmins();
        $this->view('admin/users/admins', ['admins' => $admins]);
    }

    /**
     * Traitement de la création d'un admin
     */
    /**
     * Traitement de la création d'un admin
     */
    public function createAdminHandler()
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect('/admin/createAdmin');
        }

        $post = $this->sanitizePost();
        
        $nom = trim($post['nom'] ?? '');
        $prenom = trim($post['prenom'] ?? '');
        $email = trim($post['email'] ?? ''); // Identifiant / Login
        $password = $post['password'] ?? '';

        if (empty($nom) || empty($email) || empty($password)) {
            Session::setFlash('error', 'Le nom, l\'identifiant et le mot de passe sont obligatoires.');
            $this->redirect('/admin/createAdmin');
        }

        // Vérifier la limite des 50 caractères du login de ta BDD
        if (strlen($email) > 50) {
            Session::setFlash('error', 'L\'identifiant (login) ne doit pas dépasser 50 caractères.');
            $this->redirect('/admin/createAdmin');
        }

        // Vérifier si le login existe déjà
        if ($this->adminModel->getOneAdminByLogin($email)) {
            Session::setFlash('error', 'Cet identifiant est déjà utilisé par un autre administrateur.');
            $this->redirect('/admin/createAdmin');
        }

        // Concaténation pour s'adapter à ta BDD (qui n'a que la colonne `nom`)
        $nomComplet = !empty($prenom) ? $prenom . ' ' . $nom : $nom;

        // Uniquement les colonnes réelles de ta table SQL administrateur
        $data = [
            'nom'        => $nomComplet,
            'login'      => $email,
            'motDePasse' => $password
        ];

        if ($this->adminModel->createAdmin($data)) {
            Session::setFlash('success', 'Nouvel administrateur créé avec succès.');
            $this->redirect('/admin/admins');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'enregistrement en base de données.');
            $this->redirect('/admin/createAdmin');
        }
    }

    /**
     * Liste les messages de contact
     */
    public function messages()
    {
        $this->requireAdmin();
        $messages = $this->contactModel->getAllMessages();
        $this->view('admin/contact_list', ['messages' => $messages]);
    }

    /**
     * Affiche un message de contact
     */
    public function viewMessage($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null) {
            $this->redirect('/admin/messages');
        }

        $message = $this->contactModel->getMessageById($idContact);

        if (!$message) {
            Session::setFlash('error', 'Message introuvable.');
            $this->redirect('/admin/messages');
        }

        // Marquer comme traité si pas déjà fait
        if (!$message['traite']) {
            $this->contactModel->markAsProcessed($idContact);
        }

        $this->view('admin/contact_view', ['message' => $message]);
    }

    /**
     * Supprime un message de contact
     */
    public function deleteMessage($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null) {
            $this->redirect('/admin/messages');
        }

        if ($this->contactModel->deleteMessage($idContact)) {
            Session::setFlash('success', 'Message supprimé.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }
        $this->redirect('/admin/messages');
    }
}
