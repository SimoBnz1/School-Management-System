#  EduSync - Backend Authentication System

##  Description

EduSync est une application web qui permet de gérer l'authentification des utilisateurs d'une école.
Le but principal de ce projet est de sécuriser l'accès aux données sensibles en mettant en place un système complet d'inscription, de connexion et de gestion des sessions.

##  Objectifs

* Transformer un site statique en application dynamique
* Assurer la sécurité des données utilisateurs
* Gérer l'authentification (Register / Login)
* Contrôler l'accès aux pages protégées (Dashboard)


##  Fonctionnalités

###  Inscription (Register)

* Formulaire avec : Nom, Prénom, Email, Mot de passe
* Validation des champs (champs obligatoires + format email)
* Nettoyage des données avec `htmlspecialchars()` pour éviter XSS

###  Connexion (Login)

* Vérification des identifiants (email + mot de passe)
* Affichage d'un message d'erreur en cas d'échec

###  Sécurité & Sessions

* Utilisation de `$_SESSION` pour gérer les utilisateurs connectés
* Protection du dashboard (accès uniquement si connecté)
* Déconnexion sécurisée avec `session_destroy()`

##  Structure du projet
edusync-backend/
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│ 
├── scripts/
│   ├──login_process.php
│   └──logout.php
|   └──connexion.php
|   |__register_proccess.php
├── includes/
│   ├── logo.php
│   ├── footer.php
│ 
└── README.md
```

---

## 🛠️ Technologies utilisées

* PHP 8 (Programmation procédurale)
* MySQL
* HTML / CSS
* Tailwind CSS

---

##  Logique utilisée

###  Traitement des formulaires

* Utilisation de `$_POST` pour récupérer les données
* Validation avec conditions `if/else`

###  Gestion des erreurs

* Utilisation de `$_GET` pour afficher les messages (ex: `?error=invalid`)

###  Sécurité

* Protection XSS avec `htmlspecialchars()`
* Vérification des entrées utilisateur
* Sécurisation des accès avec sessions

---

##  Installation

1. Cloner le projet :


git clone https://github.com/your-username/edusync-backend.git


2. Créer la base de données MySQL :

* Nom : `edusync`
* Importer la table `users`

3. Configurer la connexion à la base de données

4. Lancer le projet avec un serveur local (XAMPP, Laragon...)


## Démonstration (Flow)

1. Inscription d'un utilisateur
2. Connexion avec les identifiants
3. Accès au dashboard sécurisé
4. Déconnexion

##  Auteur
Mohamed Ben Izaa
Projet réalisé dans le cadre de la formation Développement Web.

