GenerationPassword
==================

Application web de génération et de test de sécurité de mot de passe, développée à l'occasion du défi sur developpez.com

Avant tout, une démonstration du fonctionnement de l'application est disponible à cette adresse : [http://generationpassword.url.ph](http://generationpassword.url.ph/)

Informations Générales 
=======================

Le site a été développé avec le framework [CakePhP](http://cakephp.org/).

On peut dénombrer 2 classes principales dans ce projet se trouvant dans le dossier [app/Model](https://github.com/ccantet/generationpassword/tree/master/app/Model) :

* **PasswordEntity** : classe du password. Contient deux principales méthodes qui sont :
    * **generatePassword** : Méthode de génération du mot de passe
    * **testPassword** : Méthode qui va tester le mot de passe
* **ManipulableString** : classe qui propose des méthodes de manipulation sur une chaîne de caractère, dans l'objectif de proposer un mot de passe fort

Les autres classes se trouvant dans app/Model sont :

* **Toolbox** : classe servant de "boîte à outils" contenant des méthodes statiques plutôt pratiques 
* **Word** : Modèle permettant d'accéder à la table words, qui contient l'ensemble des mots des dictionnaires français et anglais
* **CommonPassword** : Modèle permettant d'accéder à la table common_passwords contenant les 10 000 pires mots de passe existant (soit les 10 000 mots de passe les plus utilisés). Obtenu sur le site [xato.net](https://xato.net/passwords/more-top-worst-passwords/)

Une API complète de l'application est disponible à cette adresse : [http://generationpassword.url.ph/doc](http://generationpassword.url.ph/doc)

Comment est généré le mot de passe ?
======================================

Le mot de passe est généré à partir d'une phrase choisi par l'utilisateur. C'est phrase sert de base à la génération du mot de passe.

Qu'en fait l'algorithme ?
-------------------------

* Premièrement, il met aléatoirement la première lettre de chaque mot qui compose la phrase
* Si l'utilisateur a indiqué qu'on devait transformer cette phrase, on remplace aléatoirement des lettres par leur équivalent (ex: a => @, u => µ...) et on met aléatoirement certaines lettres en majuscule
* Ensuite, il supprime les espaces en les remplacant par des chiffres (qui suivent un ordre logique) et un caractère spécial (ordre non défini) 
* Si le mot de passe est trop petit (c-a-d de longueur inférieure à la longueur minimum requise) des caractères aléatoires sont ajoutés en début et/ou en fin de chaîne jusqu'à ce que celui-ci atteigne la taille requise
* Enfin, on s'assure que le caractère comporte au minimum un caractère minuscule, majuscule, un chiffre et un caractère spécial. Si ce n'est pas le cas, pour chaque type manquant, on en ajoute aléatoirement un au début ou à la fin du mot de passe déjà créé

A l'issue de la génération du mot de passe, celui-ci est directement testé.

Comment est testé le niveau de sécurité d'un mot de passe ?
============================================================

La sécurité d'un mot de passe dépend avant tout et surtout de sa longueur. Ainsi un mot de passe de 8 caractères, peut-importe sa composition, sera toujours considéré comme très faible, alors qu'à contrario un mot de passe de plus de 16 caractères sera considéré comme très bon.
Un mot de passe est également automatiquement considéré comme très faible si c'est un mot du dictionnaire (français ou anglais), où s'il fait parti des 10 000 passwords les plus utilisés au monde.

Le niveau de sécurité dépend du temps qu'il faudrait à un ordinateur de bureau actuel (4 coeurs, effectuant 200 000 000 000 de test à la seconde) pour casser le mot de passe par force brute.

Des conseils sont également affiché à l'utilisateur pour améliorer la qualité de son mot de passe (ajouter un chiffre, allonger sa taille...)

