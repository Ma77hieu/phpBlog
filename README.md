# How to install myPhpBlog project

### 1.Clone the repository
Open a command prompt and open the directory where you want to install the project
run `git clone https://github.com/Ma77hieu/phpBlog.git`

### 2.Install required libraries
run `composer install`

### 3.Create and setup the .env file
take the .env.example file and rename it ".env", replace all data inside this file with your own actual environment data.
If you have doubts regarding all SMTP variables, please refer to your email provider documentation.

### 4.Create your database
following instructions applies for mysql: while being in the root directory run `mysql -u username -p database_name < SQL/blog.sql`

### 5.Discover the blog
The database already contains some example data so you can discover the site not completely empty of content.