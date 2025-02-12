Knowledge Learning

I)Introduction

	"Knowledge Learning" est une plateforme e-learning et e-commerce développée pour la société "Knowledge".  
Elle permet aux utilisateurs d’acheter des cursus et des leçons pour apprendre en autonomie.  

	1) Objectif du projet
L’objectif est de proposer un espace en ligne où les utilisateurs peuvent :  
S’inscrire et activer leur compte par email  
Acheter des cursus et des leçons  
alider leurs leçons et obtenir une certification  
Gérer les contenus et utilisateurs via un backoffice (pour les administrateurs)

	2) Technologies utilisées
- Symfony 7 (Framework PHP)  
- "XAMPP"(Pour "Apache" et "MySQL" (Serveur et Base de données)  
- "Twig" (Moteur de template)  
- "Doctrine" (ORM pour la gestion des entités)  
- "Stripe" (Paiement en ligne)  
- "Mailtrap / SMTP" (Envoi d’e-mails pour l’activation de compte)  
- "Bootstrap" (Interface utilisateur)  
- "PHPUnit" (Tests unitaires)  

II) Pré-requis et Installation

	1) Pré-requis

Avant d'installer le projet, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- "PHP 8.2+"  
- "Composer"  
- "Symfony CLI" (optionnel mais recommandé)  
- "MySQL 8+"  
- "Node.js & npm" (pour gérer les assets si nécessaire)  
-

	2) Installation

Ouvrir XAMPP et lancer Apache et MySQL.

Installer les dépendances PHP avec Composer :
composer install

Configurer la base de données :
Créer un compte utilisateur en base de données avec les information suivantes :
	nom d'utilisateur : knowledge
	Mot de passe : knowledge2025
	Nom d'hôte : %
	Privilèges globaux : ALL PRIVILEGES
	"GRANT" : oui

Puis, créez la base de données et exécutez les migrations :
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

Lancer le serveur Symfony :
Symfony server:start

Le projet est maintenant accessible sur http://127.0.0.1:8000

Un utilisateur administrateur sera accessible avec les identifiants suivant pour accéder au back office :
	email : knowledge@gmail.com
	password : knowledge2025

Pour accéder aux autres parties, il faut ce créer un compte en suivant le formulaire d'inscription sur la page d'accueil.

Pour acheter un cursus ou une leçon, il faut entrer le numéro de code : 4242 4242 4242 4242
Ensuite vous pouvez entrer n'importe quels informations.

	