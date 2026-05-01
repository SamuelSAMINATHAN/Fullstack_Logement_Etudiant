-- ============================================================
--  BASE DE DONNÉES : dorocho_logements
--  Projet : Plateforme de logements étudiants (PHP MVC)
--  Version : 1.3
--  Encodage : utf8mb4 (supporte les emojis et caractères spéciaux)
--
--  ORDRE DE CRÉATION IMPORTANT (respecter les dépendances FK) :
--    1. utilisateur          — table parent centrale
--    2. etudiant             — hérite de utilisateur
--    3. bailleur             — hérite de utilisateur
--    4. administrateur       — table indépendante (login séparé)
--    5. annonce              — dépend de bailleur
--    6. photo_annonce        — dépend de annonce
--    7. candidature          — dépend de etudiant + annonce
--    8. favoris              — dépend de etudiant + annonce
--    9. avis                 — dépend de etudiant + annonce
--   10. alerte               — dépend de etudiant
--   11. message              — dépend de utilisateur (expéditeur/destinataire)
--   12. signalement          — dépend de etudiant + annonce
--   13. reset_password       — dépend de utilisateur
--   14. faq                  — table indépendante
--   15. informationlegale    — table indépendante (CGU, mentions légales)
--   16. contact              — table indépendante (Support client)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
--  SUPPRESSION DES TABLES (dans l'ordre inverse des FK)
--  Décommenter ce bloc pour réinitialiser la base proprement.
-- ============================================================
/*
DROP TABLE IF EXISTS `reset_password`;
DROP TABLE IF EXISTS `signalement`;
DROP TABLE IF EXISTS `message`;
DROP TABLE IF EXISTS `alerte`;
DROP TABLE IF EXISTS `avis`;
DROP TABLE IF EXISTS `favoris`;
DROP TABLE IF EXISTS `candidature`;
DROP TABLE IF EXISTS `photo_annonce`;
DROP TABLE IF EXISTS `annonce`;
DROP TABLE IF EXISTS `administrateur`;
DROP TABLE IF EXISTS `bailleur`;
DROP TABLE IF EXISTS `etudiant`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `faq`;
DROP TABLE IF EXISTS `informationlegale`;
DROP TABLE IF EXISTS `contact`;
*/


-- ============================================================
-- 1. TABLE : utilisateur
--    Rôles possibles : 'etudiant', 'bailleur'
--    Les admins ont leur propre table (login séparé).
--
--    RGPD :
--      - date_acceptation_cgu  : preuve du consentement au moment de l'inscription
--      - derniere_connexion    : permet de purger les comptes inactifs depuis 3 ans
--      - demande_suppression   : droit à l'effacement (Art. 17 RGPD)
--
--    Sécurité :
--      - deux_facteurs_secret  : clé TOTP pour Google Authenticator (2FA)
--      - est_2fa_active        : flag indiquant si le 2FA est activé par l'utilisateur
--
--    NOTE DÉVELOPPEUR :
--      Le champ `mdp` doit stocker un hash bcrypt (password_hash() en PHP),
--      jamais un mot de passe en clair.
-- ============================================================
CREATE TABLE `utilisateur` (
  `idUtilisateur`       INT(11)       NOT NULL AUTO_INCREMENT,
  `nom`                 VARCHAR(100)  DEFAULT NULL,
  `prenom`              VARCHAR(100)  DEFAULT NULL,
  `email`               VARCHAR(150)  NOT NULL,
  `mdp`                 VARCHAR(255)  NOT NULL               COMMENT 'Hash bcrypt — jamais en clair',
  `role`                ENUM('etudiant','bailleur')   NOT NULL               COMMENT 'etudiant | bailleur',

  -- RGPD
  `date_acceptation_cgu` DATETIME     DEFAULT CURRENT_TIMESTAMP COMMENT 'Preuve du consentement RGPD',
  `derniere_connexion`   DATETIME     DEFAULT NULL            COMMENT 'Mise à jour à chaque login — purge après 3 ans d''inactivité',
  `demande_suppression`  TINYINT(1)   DEFAULT 0               COMMENT '1 = l''utilisateur a demandé la suppression de son compte',

  -- 2FA
  `deux_facteurs_secret` VARCHAR(100) DEFAULT NULL            COMMENT 'Clé secrète TOTP (Google Authenticator)',
  `est_2fa_active`       TINYINT(1)  DEFAULT 0               COMMENT '1 = 2FA activé par l''utilisateur',

  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 2. TABLE : etudiant
--    Extension de `utilisateur` pour les profils étudiants.
--    Relation 1-1 avec utilisateur (même idUtilisateur).
--
--    NOTE DÉVELOPPEUR :
--      À la création d'un compte étudiant, insérer d'abord dans
--      `utilisateur` puis dans `etudiant` avec le même ID.
-- ============================================================
CREATE TABLE `etudiant` (
  `idUtilisateur`  INT(11)      NOT NULL,
  `dateNaissance`  DATE       DEFAULT NULL,
  `localisation`   VARCHAR(255) DEFAULT NULL               COMMENT 'Ville ou quartier recherché',

  PRIMARY KEY (`idUtilisateur`),
  CONSTRAINT `fk_etudiant_user`
    FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 3. TABLE : bailleur
--    Extension de `utilisateur` pour les propriétaires/bailleurs.
--    Relation 1-1 avec utilisateur.
--
--    estVerifie   : mis à 1 par un admin → affiche le badge "Propriétaire Vérifié"
--    estShadowban : mis à 1 par un admin → le bailleur ne voit pas qu'il est banni
--                   (ses annonces n'apparaissent plus pour les autres)
-- ============================================================
CREATE TABLE `bailleur` (
  `idUtilisateur`  INT(11)    NOT NULL,
  `estVerifie`     TINYINT(1) DEFAULT 0  COMMENT '1 = badge Propriétaire Vérifié affiché',
  `estShadowban`   TINYINT(1) DEFAULT 0  COMMENT '1 = bailleur shadowbanni (invisible pour les autres)',

  PRIMARY KEY (`idUtilisateur`),
  CONSTRAINT `fk_bailleur_user`
    FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 4. TABLE : administrateur
--    Compte séparé des utilisateurs classiques.
--    L'admin se connecte via son propre formulaire (login/mdp).
--
--    NOTE DÉVELOPPEUR :
--      Ne pas mélanger avec la table `utilisateur`.
--      Le mot de passe doit aussi être hashé en bcrypt.
-- ============================================================
CREATE TABLE `administrateur` (
  `idAdmin`    INT(11)      NOT NULL AUTO_INCREMENT,
  `nom`        VARCHAR(100) DEFAULT NULL,
  `login`      VARCHAR(50)  NOT NULL,
  `motDePasse` VARCHAR(255) NOT NULL  COMMENT 'Hash bcrypt',

  PRIMARY KEY (`idAdmin`),
  UNIQUE KEY `uk_admin_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 5. TABLE : annonce
--    Publiée par un bailleur.
--    Les photos sont dans la table photo_annonce (relation 1-N).
--
--    type_logement  : 'Appartement', 'Studio', 'Chambre', 'Colocation'...
--    meuble         : 0 = non meublé, 1 = meublé
--    estColocation  : 0 = logement entier, 1 = colocation
--    dateDisponibilite : date à partir de laquelle le logement est disponible
--
--    NOTE DÉVELOPPEUR :
--      Penser à indexer `localisation` et `prix` pour les filtres
--      de recherche avancée (voir index ci-dessous).
-- ============================================================
CREATE TABLE `annonce` (
  `idAnnonce`         INT(11)        NOT NULL AUTO_INCREMENT,
  `titre`             VARCHAR(255)   NOT NULL,
  `description`       TEXT           DEFAULT NULL,
  `prix`              DECIMAL(10,2)  DEFAULT NULL             COMMENT 'Loyer mensuel en euros',
  `localisation`      VARCHAR(255)   DEFAULT NULL,
  `type_logement`     VARCHAR(50)    DEFAULT NULL             COMMENT 'Appartement | Studio | Chambre | Colocation',
  `surface`           INT(11)        DEFAULT NULL             COMMENT 'Surface en m²',
  `nbPieces`          INT(11)        DEFAULT 1,
  `meuble`            TINYINT(1)     DEFAULT 0                COMMENT '0 = non meublé, 1 = meublé',
  `estColocation`     TINYINT(1)     DEFAULT 0                COMMENT '0 = logement entier, 1 = colocation',
  `dateDisponibilite` DATE           DEFAULT NULL             COMMENT 'Date à partir de laquelle le logement est disponible',
  `datePublication`   DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `idBailleur`        INT(11)        NOT NULL,

  PRIMARY KEY (`idAnnonce`),
  KEY `idx_annonce_bailleur`    (`idBailleur`),
  KEY `idx_annonce_localisation` (`localisation`),
  KEY `idx_annonce_prix`         (`prix`),

  CONSTRAINT `fk_annonce_bailleur`
    FOREIGN KEY (`idBailleur`) REFERENCES `bailleur` (`idUtilisateur`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 6. TABLE : photo_annonce
--    Stocke les URLs des photos liées à une annonce (1 annonce → N photos).
--
--    NOTE DÉVELOPPEUR :
--      urlPhoto doit contenir le chemin relatif depuis la racine web,
--      ex : /uploads/annonces/42/photo1.jpg
--      L'upload physique des fichiers est géré côté PHP.
-- ============================================================
CREATE TABLE `photo_annonce` (
  `idPhoto`   INT(11)      NOT NULL AUTO_INCREMENT,
  `urlPhoto`  VARCHAR(255) NOT NULL,
  `idAnnonce` INT(11)      NOT NULL,

  PRIMARY KEY (`idPhoto`),
  KEY `idx_photo_annonce` (`idAnnonce`),

  CONSTRAINT `fk_photo_annonce`
    FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 7. TABLE : candidature
--    Un étudiant postule à une annonce.
--    statut : 'Envoyée' | 'Vue' | 'Acceptée' | 'Refusée'
--
--    NOTE DÉVELOPPEUR :
--      Un étudiant ne devrait pas pouvoir postuler deux fois
--      à la même annonce → ajouter une contrainte UNIQUE si besoin :
--      UNIQUE KEY `uk_cand_etudiant_annonce` (`idEtudiant`, `idAnnonce`)
-- ============================================================
CREATE TABLE `candidature` (
  `idCandidature` INT(11)     NOT NULL AUTO_INCREMENT,
  `message`       TEXT        DEFAULT NULL,
  `statut`        ENUM('Envoyée','Vue','Acceptée','Refusée') DEFAULT 'Envoyée'             COMMENT 'Envoyée | Vue | Acceptée | Refusée',
  `dateEnvoi`     DATETIME    DEFAULT CURRENT_TIMESTAMP,
  `idEtudiant`    INT(11)     NOT NULL,
  `idAnnonce`     INT(11)     NOT NULL,
  

  PRIMARY KEY (`idCandidature`),
  UNIQUE KEY `uk_candidature` (`idEtudiant`, `idAnnonce`),
  KEY `idx_cand_etudiant` (`idEtudiant`),
  KEY `idx_cand_annonce`  (`idAnnonce`),

  CONSTRAINT `fk_cand_etudiant`
    FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`),
  CONSTRAINT `fk_cand_annonce`
    FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 8. TABLE : favoris
--    Un étudiant met une annonce en favori.
--    Clé primaire composite (idEtudiant, idAnnonce) → un seul favori par annonce.
-- ============================================================
CREATE TABLE `favoris` (
  `idEtudiant` INT(11)  NOT NULL,
  `idAnnonce`  INT(11)  NOT NULL,
  `dateAjout`  DATETIME DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`idEtudiant`, `idAnnonce`),
  KEY `idx_fav_annonce` (`idAnnonce`),

  CONSTRAINT `fk_fav_etudiant`
    FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_fav_annonce`
    FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 9. TABLE : avis
--    Un étudiant laisse un avis sur une annonce (note + commentaire).
--    note : valeur entière, ex. de 1 à 5 → à valider côté PHP.
--
--    NOTE DÉVELOPPEUR :
--      Pensez à vérifier qu'un étudiant ne peut laisser qu'un seul avis
--      par annonce (contrainte UNIQUE à ajouter si souhaité).
-- ============================================================
CREATE TABLE `avis` (
  `idAvis`       INT(11)  NOT NULL AUTO_INCREMENT,
  `note`         INT(11) NOT NULL CHECK (`note` BETWEEN 1 AND 5),
  `commentaire`  TEXT     DEFAULT NULL,
  `dateAvis`     DATETIME DEFAULT CURRENT_TIMESTAMP,
  `idEtudiant`   INT(11)  NOT NULL,
  `idAnnonce`    INT(11)  NOT NULL,

  PRIMARY KEY (`idAvis`),
  UNIQUE KEY uk_avis (idEtudiant, idAnnonce),
  KEY `idx_avis_etudiant` (`idEtudiant`),
  KEY `idx_avis_annonce`  (`idAnnonce`),

  CONSTRAINT `fk_avis_etudiant`
    FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`),
  CONSTRAINT `fk_avis_annonce`
    FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 10. TABLE : alerte
--     Un étudiant configure une alerte de recherche.
--     Quand une nouvelle annonce correspond aux critères,
--     l'étudiant reçoit une notification (email ou interne).
--
--     NOTE DÉVELOPPEUR :
--       Le déclenchement des alertes peut être fait via un CRON PHP
--       qui compare les nouvelles annonces aux alertes existantes.
-- ============================================================
CREATE TABLE `alerte` (
  `idAlerte`    INT(11)       NOT NULL AUTO_INCREMENT,
  `idEtudiant`  INT(11)       NOT NULL,
  `localisation` VARCHAR(255) DEFAULT NULL,
  `budgetMax`   DECIMAL(10,2) DEFAULT NULL,
  `colocation`  TINYINT(1)    DEFAULT 0   COMMENT '1 = cherche spécifiquement une colocation',

  PRIMARY KEY (`idAlerte`),
  KEY `idx_alerte_etudiant` (`idEtudiant`),

  CONSTRAINT `fk_alerte_etudiant`
    FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 11. TABLE : message
--     Messagerie interne entre deux utilisateurs quelconques
--     (étudiant ↔ bailleur, étudiant ↔ étudiant, etc.).
--     idExpediteur et idDestinataire référencent `utilisateur`.
--
--     estLu : 0 = non lu, 1 = lu par le destinataire
--
--     NOTE DÉVELOPPEUR :
--       Pour afficher une conversation, récupérer tous les messages
--       WHERE (idExpediteur = X AND idDestinataire = Y)
--          OR (idExpediteur = Y AND idDestinataire = X)
--       ORDER BY dateEnvoi ASC
-- ============================================================
CREATE TABLE `message` (
  `idMessage`      INT(11)  NOT NULL AUTO_INCREMENT,
  `contenu`        TEXT     NOT NULL,
  `dateEnvoi`      DATETIME DEFAULT CURRENT_TIMESTAMP,
  `estLu`          TINYINT(1) DEFAULT 0  COMMENT '0 = non lu, 1 = lu',
  `idExpediteur`   INT(11)  NOT NULL,
  `idDestinataire` INT(11)  NOT NULL,

  PRIMARY KEY (`idMessage`),
  KEY `idx_msg_expediteur`   (`idExpediteur`),
  KEY `idx_msg_destinataire` (`idDestinataire`),

  CONSTRAINT `fk_msg_expediteur`
    FOREIGN KEY (`idExpediteur`)   REFERENCES `utilisateur` (`idUtilisateur`),
  CONSTRAINT `fk_msg_destinataire`
    FOREIGN KEY (`idDestinataire`) REFERENCES `utilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 12. TABLE : signalement
--     Un étudiant signale une annonce frauduleuse ou abusive.
--     statut : 'En attente' | 'Traité' | 'Rejeté'
--
--     NOTE DÉVELOPPEUR :
--       Les signalements sont visibles dans le backoffice admin.
--       L'admin peut ensuite supprimer l'annonce ou shadowban le bailleur.
-- ============================================================
CREATE TABLE `signalement` (
  `idSignalement`   INT(11)     NOT NULL AUTO_INCREMENT,
  `motif`           TEXT        NOT NULL,
  `dateSignalement` DATETIME    DEFAULT CURRENT_TIMESTAMP,
  `statut`          ENUM('En attente','Traité','Rejeté') DEFAULT 'En attente'   COMMENT 'En attente | Traité | Rejeté',
  `idEtudiant`      INT(11)     NOT NULL,
  `idAnnonce`       INT(11)     NOT NULL,

  PRIMARY KEY (`idSignalement`),
  KEY `idx_sign_etudiant` (`idEtudiant`),
  KEY `idx_sign_annonce`  (`idAnnonce`),

  CONSTRAINT `fk_sign_etudiant`
    FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`),
  CONSTRAINT `fk_sign_annonce`
    FOREIGN KEY (`idAnnonce`)  REFERENCES `annonce` (`idAnnonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 13. TABLE : reset_password
--     Gestion des tokens de réinitialisation de mot de passe.
--     Flux : l'utilisateur demande → on génère un token unique
--            → on envoie le lien par email → l'utilisateur clique
--            → on vérifie token non expiré et non utilisé
--            → on met à jour le mdp → on met utilise = 1.
--
--     NOTE DÉVELOPPEUR :
--       - token = bin2hex(random_bytes(32)) en PHP
--       - expiration = NOW() + 1 heure (recommandé)
--       - Après utilisation, mettre `utilise` = 1 (ne pas supprimer,
--         pour éviter la réutilisation du token)
-- ============================================================
CREATE TABLE `reset_password` (
  `idReset`       INT(11)      NOT NULL AUTO_INCREMENT,
  `idUtilisateur` INT(11)      NOT NULL,
  `token`         VARCHAR(255) NOT NULL  COMMENT 'Token unique généré par bin2hex(random_bytes(32))',
  `expiration`    DATETIME     NOT NULL  COMMENT 'Généralement NOW() + 1 heure',
  `utilise`       TINYINT(1)   DEFAULT 0 COMMENT '1 = token déjà consommé, ne plus accepter',

  PRIMARY KEY (`idReset`),
  UNIQUE KEY `uk_token` (`token`),
  KEY `idx_reset_user` (`idUtilisateur`),

  CONSTRAINT `fk_reset_user`
    FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 14. TABLE : faq
--     Questions / Réponses gérées par les admins (backoffice).
--
--     NOTE DÉVELOPPEUR :
--       Ajouter un champ `ordre` INT si l'on veut trier manuellement
--       les questions dans le backoffice.
-- ============================================================
CREATE TABLE `faq` (
  `idFAQ`    INT(11) NOT NULL AUTO_INCREMENT,
  `question` TEXT    NOT NULL,
  `reponse`  TEXT    NOT NULL,

  PRIMARY KEY (`idFAQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 15. TABLE : informationlegale
--     Stocke les pages légales : CGU, Mentions légales, etc.
--     Gérée par les admins via le backoffice.
--
--     NOTE DÉVELOPPEUR :
--       `titre` permet de distinguer les pages :
--        ex. 'CGU', 'Mentions légales', 'Politique de confidentialité'
--       `dateMiseAJour` se met à jour automatiquement à chaque modification.
-- ============================================================
CREATE TABLE `informationlegale` (
  `idInfo`         INT(11)      NOT NULL AUTO_INCREMENT,
  `titre`          VARCHAR(255) DEFAULT NULL   COMMENT 'CGU | Mentions légales | Politique de confidentialité',
  `contenu`        TEXT         DEFAULT NULL,
  `dateMiseAJour`  DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`idInfo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 16. TABLE : contact
--     Stocke les messages envoyés via le formulaire de contact.
--     Utilisable par des visiteurs non connectés ou des membres.
--
--     NOTE DÉVELOPPEUR :
--       - `traite` permet à l'admin de marquer le message comme géré.
--       - Utile pour le support client et les questions avant inscription.
-- ============================================================

CREATE TABLE `contact` (
  `idContact` INT(11)      NOT NULL AUTO_INCREMENT,
  `nom`       VARCHAR(100) DEFAULT NULL,
  `email`     VARCHAR(150) NOT NULL,
  `sujet`     VARCHAR(255) DEFAULT NULL,
  `message`   TEXT         NOT NULL,
  `dateEnvoi` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `traite`    TINYINT(1)   DEFAULT 0               COMMENT '1 = traité par admin',

  PRIMARY KEY (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
--  FIN DU SCRIPT
-- ============================================================
COMMIT;