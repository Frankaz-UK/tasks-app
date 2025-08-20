**Setup instructions**

**The following steps require this software to be installed on your machine:**
- [PHP 8 (or higher)](https://www.php.net/manual/en/install.php)
- [Composer](https://getcomposer.org/download/)
- [Docker with Docker Compose](https://docs.docker.com/compose/install/)
- [Node](https://nodejs.org/en/download)
- [NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

**1. Open the repository folder in your terminal**

run `composer install`

run `npm install`

**2. Copy the .env.local.example file to .env**

**3. run `php artisan key:generate` to generate a new app key.**

**4. You will need three tabs open in your terminal in the repositories folder for the following section:**

run `./vendor/bin/sail up`

run `npm run dev`

run `./vendor/bin/sail artisan migrate:fresh --seed` (adds entries to the database for users with roles and tasks)

**5. Navigate to http://localhost**

**6. Logins can be found in the users seeder, all passwords are *password*.**
