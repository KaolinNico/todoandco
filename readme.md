### Informations utiles

> **Version PHP :** 7.1  
> **Base de données :** MySQL 5.7  
> **Qualité du code :** [![Codacy Badge](https://app.codacy.com/project/badge/Grade/3c94187bcd8d4abfacc6720364c4ce43)](https://www.codacy.com/manual/Nicolas_21/todoandco/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=KaolinNico/todoandco&amp;utm_campaign=Badge_Grade)

### Installation

* Se positionner dans le répertoire souhaité et récupérer le projet à l'aide de la commande
```
git clone https://github.com/KaolinNico/todoandco.git
```
* Modifier le fichier .env avec vos informations de base de données et email
* Pour installer l'ensemble des dépendances nécessaires au fonctionnement du site, éxécutez la commande suivante
```
composer install
```
* Exécutez les commandes suivantes pour installer la base de données
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
* Pour intégrer les données de démo dans la base données, éxécutez la commande suivante
```
php bin/console hautelook:fixtures:load
```

Il est maintenant possible de se connecter avec un compte utilisateur d'exemple :
> utilisateur : demo_user  
> mot de passe : 1Utilisateur!

### Contribuer au projet

Vous souhaitez contribuer au projet, reportez vous au [CONTRIBUTING](https://github.com/KaolinNico/todoandco/blob/develop/CONTRIBUTING.md) pour connaître les modalités.