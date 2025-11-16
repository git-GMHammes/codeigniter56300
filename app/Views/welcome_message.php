<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CodeIgniter 4</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .version {
            color: #764ba2;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        p {
            line-height: 1.8;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
        }

        .links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .links a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .info h2 {
            color: #667eea;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .info ul {
            list-style: none;
            text-align: left;
        }

        .info li {
            padding: 0.5rem 0;
            color: #666;
        }

        .info li strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <svg class="logo" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
            <circle cx="128" cy="128" r="120" fill="#667eea"/>
            <path d="M128 40 L200 90 L200 166 L128 216 L56 166 L56 90 Z" fill="none" stroke="white" stroke-width="8" stroke-linejoin="round"/>
            <circle cx="128" cy="128" r="30" fill="white"/>
        </svg>
        
        <h1>Welcome to CodeIgniter 4</h1>
        <p class="version">Your application is now running!</p>
        
        <p>
            This is a basic CodeIgniter 4 setup. The framework is designed with performance 
            and modern PHP practices in mind, providing you with a solid foundation for building 
            dynamic web applications.
        </p>

        <div class="info">
            <h2>Getting Started</h2>
            <ul>
                <li><strong>Environment:</strong> Development</li>
                <li><strong>PHP Version:</strong> <?= PHP_VERSION ?></li>
                <li><strong>CodeIgniter:</strong> Version 4.x</li>
            </ul>
        </div>

        <div class="links">
            <a href="https://codeigniter.com/user_guide/" target="_blank">Documentation</a>
            <a href="https://github.com/codeigniter4/CodeIgniter4" target="_blank">GitHub</a>
            <a href="https://forum.codeigniter.com/" target="_blank">Forum</a>
        </div>
    </div>
</body>
</html>
