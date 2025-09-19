**Setup instructions**

**The following steps require this software to be installed on your machine:**
- [PHP 8 (or higher)](https://www.php.net/manual/en/install.php)
- [Composer](https://getcomposer.org/download/)
- [Docker with Docker Compose](https://docs.docker.com/compose/install/)
- [Node](https://nodejs.org/en/download)
- [NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

**1. Open the repository root folder in your terminal**

run `composer install`

run `npm install`

If you have any errors with the packages please check with the [npm](https://www.npmjs.com/package/) database or [packagist](https://packagist.org/) (composer) database.

**2. In the repository root folder copy the .env.local.example file to .env**

**3. In your terminal run `php artisan key:generate` to generate a new app key.**

**4. You will need three tabs open in your terminal in the repository root folder for the following section:**

Tab 1:
run `./vendor/bin/sail up`

Tab 2:
run `npm run dev`

Tab 3:
run `./vendor/bin/sail artisan migrate:fresh --seed` (adds entries to the database for users with roles and tasks)

**5. Navigate to http://localhost**

**6. Logins can be found in the users seeder, all passwords are *password*.**
