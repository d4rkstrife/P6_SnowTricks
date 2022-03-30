# P6_snowtricks

Project installation
GIT installation :

- GIT (https://git-scm.com/downloads) 
When GIT is installed, go in the folder of your choice and then execute this command:

- git clone https://github.com/d4rkstrife/P6_snowtricks.git
The project will be automatically copied in the folder.

In .env file, modifiate 
- DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
with your own database informations.
Then modifiate 
- MAILER_DSN=smtp://localhost:1025
with you mail informations.

Then you have to install all dependencies the app needed to run:
Run in this order: 
- composer install
- npm install
- yarn build

To create the database and tables, run:

- symfony console doctrine:database:create
- symfony console dctrine:migrations:migrate

You can generate fake datas with fixtures
Run:

- symfony console doctrine:fixtures:load

Then to launch the application, run
- symfony serve
- yarn watch





[![Codacy Badge](https://app.codacy.com/project/badge/Grade/af3b4ac8d6a14f55a8ad667585af5ec4)](https://www.codacy.com/gh/d4rkstrife/P6_snowtricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=d4rkstrife/P6_snowtricks&amp;utm_campaign=Badge_Grade)


