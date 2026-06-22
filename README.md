
# PromoUnité — Gestion documentaire pour environnements éducatifs

PromoUnité est une application web que j'ai construite en PHP/MySQL pour répondre à un besoin concret : centraliser et sécuriser l'accès aux ressources pédagogiques au sein d'un intranet éducatif. Cours, TD, évaluations, normes — tout est regroupé en un seul endroit, accessible uniquement aux utilisateurs autorisés.

---

## Le contexte

Dans beaucoup d'établissements, les documents pédagogiques sont éparpillés : drives partagés, emails, clés USB... PromoUnité simule ce qu'un vrai système interne devrait être — une plateforme fermée, structurée, où chaque utilisateur accède à ce dont il a besoin après authentification, sans que rien ne soit exposé publiquement.

---

## Ce que fait l'application

**Côté authentification**, chaque utilisateur se connecte avec ses identifiants personnels. Une fois connecté, il dispose d'une page dédiée pour mettre à jour son profil. Les sessions PHP assurent la continuité de la navigation, et les routes sensibles restent protégées contre tout accès non authentifié.

**Côté documents**, l'application permet d'ajouter, classer et retrouver facilement des ressources selon leur type : cours, travaux dirigés, évaluations ou normes. Le filtrage facilite la navigation même quand le volume de contenus grossit.

**Côté administration**, une interface dédiée permet de gérer les utilisateurs, uploader des fichiers et effectuer toutes les opérations CRUD sur les documents. Elle est logiquement séparée du reste de l'application.

---

## Les choix techniques

J'ai opté pour du **PHP natif** côté backend, couplé à **MySQL** via **PDO** pour des requêtes préparées — ce qui évite les injections SQL sans dépendre d'un ORM. Le frontend est en HTML5/CSS3/JavaScript, sans framework lourd, pour garder la stack simple et maîtrisée de bout en bout.

Sur la sécurité, plusieurs couches sont en place : sessions PHP, validation des fichiers uploadés, séparation claire entre les espaces admin, API et configuration.

---

## Accès & démo

La plateforme n'est pas publique — les accès sont fournis aux utilisateurs autorisés uniquement.

- 🔗 Démo : (en cours)
- 💻 Code source : [github.com/Mawouegnigan/promounite](https://github.com/Mawouegnigan/promounite)

---

## Ce que ce projet m'a apporté

Au-delà du code, ce projet m'a permis de penser un système complet — de la gestion des droits d'accès à l'organisation des fichiers, en passant par la sécurisation des routes. C'est le genre de problématique qu'on retrouve dans des environnements institutionnels réels, et c'était justement l'objectif : simuler quelque chose de proche de la réalité.

