
# PromoUnité — Plateforme documentaire pour promotions de fonctionnaires en formation

PromoUnité est une application web que j'ai construite en PHP/MySQL pour une promotion de futurs fonctionnaires d'État, actuellement en formation. L'idée de départ est simple : pendant leur cursus, les apprenants ont besoin de partager et retrouver facilement leurs documents — qu'il s'agisse de cours, de travaux dirigés, d'évaluations ou de textes administratifs officiels. Plutôt que de s'éparpiller sur des groupes WhatsApp ou des drives mal organisés, tout est centralisé en un seul endroit, sécurisé et accessible uniquement aux membres de la promotion.

---

## Le contexte

Une promotion nouvellement recrutée, des centaines de documents qui circulent, des textes réglementaires à retrouver au bon moment... PromoUnité répond à ce besoin concret en simulant ce qu'un vrai intranet de formation devrait être : une plateforme fermée, structurée, où chaque membre accède aux ressources dont il a besoin après authentification, sans que rien ne soit exposé à l'extérieur.

---

## Ce que fait l'application

**Côté authentification**, chaque membre de la promotion se connecte avec ses identifiants personnels. Une fois connecté, il dispose d'une page dédiée pour mettre à jour son profil. Les sessions PHP assurent la continuité de la navigation, et les routes sensibles restent protégées contre tout accès non authentifié.

**Côté documents**, l'application permet d'ajouter, classer et retrouver facilement des ressources selon leur nature : cours, travaux dirigés, évaluations, normes ou documents administratifs officiels. Le filtrage facilite la navigation même quand le volume de contenus grossit au fil de la formation.

**Côté administration**, une interface dédiée permet de gérer les membres, uploader des fichiers et effectuer toutes les opérations CRUD sur les documents. Elle est logiquement séparée du reste de l'application.

---

## Architecture du projet

Le projet suit une organisation claire, pensée pour séparer les responsabilités et faciliter la maintenance :

```
promounite/
├── config/
│   ├── db.php              # Connexion à la base de données
│   └── app.php             # Configuration générale
├── admin/
│   ├── admin_dashboard.php
│   ├── admin_documents.php
│   ├── admin_documents_edit.php
│   ├── admin_users.php
│   ├── admin_actualites.php
│   ├── admin_annonces.php
│   ├── admin_avis.php
│   └── admin_statistique.php
├── assets/
│   ├── css/                # Feuilles de style (global, admin, responsive)
│   ├── js/                 # Scripts front-end
│   ├── images/             # Logo, bannières
│   └── uploads/
│       ├── documents/      # Fichiers pédagogiques et administratifs
│       └── actualites/     # Images des actualités
├── templates/
│   ├── header.php
│   ├── footer.php
│   └── floating_back.php
├── PHPMailer/              # Envoi d'emails (reset mot de passe, vérification)
├── index.php               # Page d'accueil
├── login.php               # Connexion
├── inscription.php         # Inscription
├── logout.php              # Déconnexion
├── profile.php             # Mise à jour du profil utilisateur
├── documents.php           # Consultation des documents
├── forgot_password.php     # Mot de passe oublié
├── reset_password.php      # Réinitialisation du mot de passe
├── verify_email.php        # Vérification de l'email
├── mentions.php            # Mentions légales
├── stats.php               # Statistiques
└── base_promounite.sql     # Structure de la base de données
```

La configuration est isolée dans `config/`, l'espace admin est entièrement séparé des pages membres, et les fichiers uploadés sont organisés par catégorie dans `assets/uploads/`.

---

## Les choix techniques

J'ai opté pour du **PHP natif** côté backend, couplé à **MySQL** via **PDO** pour des requêtes préparées — ce qui évite les injections SQL sans dépendre d'un ORM. Le frontend est en HTML5/CSS3/JavaScript, sans framework lourd, pour garder la stack simple et maîtrisée de bout en bout.

Sur la sécurité, plusieurs couches sont en place : sessions PHP, validation des fichiers uploadés, séparation claire entre les espaces admin et membres, et gestion de la configuration à l'écart du reste du code.

---

## Accès & démo

La plateforme n'est pas publique — les accès sont réservés aux membres de la promotion.

- 🔗 Démo : *(en cours)*
- 💻 Code source : [github.com/Mawouegnigan/promounite](https://github.com/Mawouegnigan/promounite)

---

## Ce que ce projet m'a apporté

Ce projet m'a confronté à des problématiques concrètes : comment organiser des droits d'accès proprement, comment structurer un système de fichiers évolutif, comment sécuriser une application sans sur-complexifier la stack. Travailler sur un vrai besoin — celui d'une promotion réelle en formation — a rendu chaque décision technique plus tangible et plus motivante.

---
