# Project - Gamestore - Myriam Kühn

This is my project as part of my Graduate Web Developer and Mobile Web training.

Gamestore is a fictitious company specializing in the sale of video games for all platforms known to date. They currently have 5 stores spread across France in Nantes, Lille, Bordeaux, Paris and Toulouse. Each of these stores is managed by several employees. José is the sole manager of the company.

At present, Gamestore doesn't have a website and only works with flyers, which is why they're losing customers. So they need a tool that will give them better visibility and enable online reservation of video games.


## Local Application Deployment

### Requirements

Before deploying the application, make sure you have installed the following :

- PHP (version 8.3 or higher)
- Composer 
- MySQL
- MongoDB (PHP extension)
- Apache or Nginx (or other web server)
- Node.js and npm (to collect JS modules with Rollup)
- Git (to clone the repository, if necessary)


### Clone the Git repository

```bash
git clone https://github.com/MyriamKuhn/Gamestore.git
cd your-directory
```
  
### Configuring the database

Create a database for the application in MySQL and import the database schema : 
(the necessary file can be found in the git in the "documentation" folder then "db")

```bash
mysql -u username -p database < linktomydatabase.sql
```

### Installing MongoDB

Make sure the MongoDB server is started.

### Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`DB_HOST` (MySQL Database host)

`DB_NAME` (MySQL Database name)

`DB_USER` (MySQL Database username)

`DB_PASSWORD` (MySQL Database password)

`MAILER_HOST` (the smtp for PHP Mailer)

`MAILER_PORT` (the port for the PHP Mailer)

`MAILER_EMAIL` (the email for the PHP Mailer)

`MAILER_PASSWORD` (the password for the PHP Mailer)

`SITE_RECAPTCHA_KEY` (your site recaptcha key)

`SITE_RECAPTCHA_SECRET` (your site recaptcha secret)

`MONGODB_URI` (your MongoDB Uri)

`MONGODB_DATABASE` (your MongoDB database name)

`MONGODB_COLLECTION` (your MongoDB collection name)


### Last Steps
Start your server and open a web browser and go to http://localhost (or to the address configured for your server).


## Troubleshooting

- 500 Internal Server Error: Check web server logs and file permissions.
- Database Problems : Make sure that the connection parameters in .env are correct and that the database is accessible.


## Documentation

[Documentation](https://github.com/MyriamKuhn/Gamestore/tree/main/documentation)



## Deployed Application

[https://gamestore.myriamkuhn.com](https://gamestore.myriamkuhn.com)



## Feedback

If you have any feedback, please reach out to us at myriam.kuehn@free.fr

