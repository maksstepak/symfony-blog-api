# Blog API

This is a blog REST API.

## 🔧 Technologies

- [Symfony 5](https://symfony.com/)
- [MariaDB](https://mariadb.org/)

## 🛠️ Setup

### Prerequisites

- PHP >= 7.4
- Composer
- MariaDB >= 10.5

### Installation

```bash
# Clone the repo
git clone https://github.com/maksstepak/symfony-blog-api.git

# Navigate into the directory
cd symfony-blog-api

# Copy the .env file and make the required configuration changes in the .env.local file
cp .env .env.local

# Install depedencies
composer install

# Generate the SSL keys
php bin/console lexik:jwt:generate-keypair

# Create the database
php bin/console doctrine:database:create

# Execute migrations
php bin/console doctrine:migrations:migrate

# Load fixtures
php bin/console doctrine:fixtures:load

# Run the app
php -S localhost:8000 -t public
# You can also use Symfony CLI
symfony server:start
```

## ⚙️ API endpoints

<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>Endpoint</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>POST</td>
            <td>/login</td>
            <td>Login a user</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/register</td>
            <td>Register a user</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/me</td>
            <td>Show authenticated user</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/users?limit=10&offset=0</td>
            <td>Show users</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/users/{userId}</td>
            <td>Show a user</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/users/{userId}/articles</td>
            <td>Show articles added by a user</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/articles?limit=10&offset=0</td>
            <td>Show articles</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/articles/{articleId}</td>
            <td>Show an article</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/articles</td>
            <td>Create an article</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/articles/{articleId}</td>
            <td>Update an article</td>
        </tr>
        <tr>
            <td>DELETE</td>
            <td>/articles/{articleId}</td>
            <td>Delete an article</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/articles/{articleId}/comments</td>
            <td>Show comments on an article</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/articles/{articleId}/comments</td>
            <td>Create a comment on an article</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/comments/{commentId}</td>
            <td>Show a comment</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/comments/{commentId}</td>
            <td>Update a comment</td>
        </tr>
        <tr>
            <td>DELETE</td>
            <td>/comments/{commentId}</td>
            <td>Delete a comment</td>
        </tr>
    </tbody>
</table>

## 🚀 Demo

Check it here: [Blog API](https://symfony-blog-api.herokuapp.com/)
