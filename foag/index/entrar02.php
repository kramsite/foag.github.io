<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="processa_login.php">
        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI';
            background: url('../img/ia.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
        }

        .login-container {
            background-color: rgba(0, 76, 190, 0.95);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 15px 20px rgba(0, 0, 0, 0.2);
            max-width: 360px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 24px;
            color: #333;
            color: white;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-container input,
        .login-container button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-container input {
            border: 1px solid #ccc;
        }

        .login-container button {
            background-color: rgb(25, 51, 135);
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-container button:hover {
            background-color: rgb(21, 41, 108);
        }

        .cadastro-link {
            margin-top: 8px;
        }

        .cadastro-link a {
            color: #ffffff;
            font-size: 14px;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .cadastro-link a:hover {
            color: #d1e0ff;
            text-decoration: none;
        }

        .erro {
            background-color: #ffe0e0;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="login-container">
            <h2>Seja Bem-vindo!</h2>

            <form action="../oque/oque.php" method="post">
                <input type="text" name="nome" placeholder="Usuário" required autocomplete="username">
                <input type="password" name="senha" placeholder="Senha" required autocomplete="current-password">
                <p class="cadastro-link"><a href="../cadastro/cadastro.php">Cadastre-se</a></p>
                <button type="submit">Entrar</button>
            </form>

        </div>
    </div>
</body>
</html>

