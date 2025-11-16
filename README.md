# CodeIgniter 4 Application

This is a CodeIgniter 4 application starter. CodeIgniter is a powerful PHP framework with a very small footprint, built for developers who need a simple and elegant toolkit to create full-featured web applications.

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL or other supported database (optional)

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/git-GMHammes/codeigniter56300.git
   cd codeigniter56300
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your `.env` file with your database and application settings.

## Usage

### Development Server

To start the built-in development server:

```bash
php spark serve
```

Then open your browser and navigate to `http://localhost:8080`

### CLI Commands

CodeIgniter includes a CLI tool called `spark` for various tasks:

```bash
php spark list                  # List all available commands
php spark make:controller MyController  # Create a new controller
php spark make:model MyModel            # Create a new model
php spark migrate                       # Run database migrations
```

## Project Structure

```
codeigniter56300/
├── app/
│   ├── Config/         # Configuration files
│   ├── Controllers/    # Controllers
│   ├── Models/         # Models
│   └── Views/          # Views
├── public/             # Web root
│   └── index.php       # Front controller
├── writable/           # Writable directories (cache, logs, etc.)
├── tests/              # Test files
├── vendor/             # Composer dependencies
├── .env.example        # Example environment file
├── composer.json       # Composer configuration
└── spark               # CLI tool
```

## Features

- Clean MVC architecture
- PSR-4 autoloading
- Built-in development server
- Database abstraction layer
- Form validation
- Security tools
- Session management
- Email library
- Image manipulation
- File uploading
- RESTful API support

## Documentation

- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [CodeIgniter 4 API Reference](https://codeigniter.com/api/)

## Contributing

Feel free to contribute to this project by submitting issues or pull requests.

## License

This project is open-sourced software licensed under the MIT license.