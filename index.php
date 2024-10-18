<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$servername = "localhost:3306";
	$username = "root";
	$password = "";
	$database = "banco_tcc";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove caracteres não numéricos
    $dob = $_POST['dob'];

    // Verifique se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) {
        die("CPF deve ter exatamente 11 dígitos.");
    }

    $photo = file_get_contents($_FILES['photo']['tmp_name']);

    if (!empty($name) && !empty($phone) && !empty($cpf) && !empty($dob)) {
        $sql = "INSERT INTO contatos (nome, telefone, foto, `Data`, CPF) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }

        $stmt->bind_param("sssss", $name, $phone, $photo, $dob, $cpf);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='error'>Erro ao adicionar o contato: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='error'>Por favor, preencha todos os campos.</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #333;
        }

        header {
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .navbar-toggle {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .form {
            display: flex;
            flex-direction: column;
            align-self: center;
            font-family: inherit;
            gap: 10px;
            padding-inline: 2em;
            padding-bottom: 0.4em;
            background-color: #171717;
            border-radius: 20px;
        }

        .form-container {
            width: 300px; /* Diminuindo a largura do formulário */
            background-color: #171717;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-heading {
            text-align: center;
            margin-bottom: 20px;
            color: #64ffda;
            font-size: 1.2em;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .input-field {
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            background-color: #282828;
            color: white;
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.4), inset -2px -2px 5px rgba(50, 50, 50, 0.2); /* Sombra interna para o efeito afundado */
            transition: box-shadow 0.3s ease; /* Transição suave */
        }

        .input-field:focus {
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.6), inset -2px -2px 5px rgba(50, 50, 50, 0.3); /* Aumenta o efeito quando o campo está focado */
        }

        .sendMessage-btn {
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            background-color: #171717;
            color: #64ffda;
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Adiciona transição para a sombra */
            margin-bottom: 5%;
            border: 1px solid #64ffda;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3); /* Adiciona profundidade */
        }

        .sendMessage-btn:hover {
            background-color: #52e1c7;
            color: #171717;
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4); /* Aumenta a profundidade ao passar o mouse */
        }

        .form-card1 {
            background-image: linear-gradient(163deg, #64ffda 0%, #64ffda 100%);
            border-radius: 22px;
            transition: all 0.3s;
            margin-left: 35%;
            margin-right: 35%;
            margin-bottom: 50px;
        }

        .form-card1:hover {
            box-shadow: 0px 0px 30px 1px rgba(100, 255, 218, 0.3);
        }

        .form-card2 {
            border-radius: 0;
            transition: all 0.2s;
        }

        .form-card2:hover {
            transform: scale(0.98);
            border-radius: 20px;
        }

        #imagePreview {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        #imagePreview img {
            width: 100px; /* Ajustando o tamanho da imagem */
            height: 100px;
            border-radius: 50%; /* Deixando a imagem circular */
            object-fit: cover; /* Garantindo que a imagem fique bem ajustada */
        }
    </style>
</head>
<body>
    <header>
        <h1>Cadastro de Alunos</h1>
        <span class="navbar-toggle" onclick="openNav()">
            <i class="fas fa-bars"></i>
        </span>
    </header>

    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="#">Home</a>
        <a href="./index.php">Cadastro</a>
        <a href="./list_contacts.php">Lista de Alunos</a>
        <a href="http://127.0.0.1:5000/">Captura Facial</a>
    </div>

    <div class="form-card1">
        <div class="form-card2">
            <form class="form" method="POST" enctype="multipart/form-data">
                <p class="form-heading">Cadastro de Alunos</p>

                <div class="form-field">
                    <input required placeholder="Nome" class="input-field" type="text" name="name" id="name">
                </div>

                <div class="form-field">
                    <input required placeholder="Telefone" class="input-field" type="text" name="phone" id="phone">
                </div>

                <div class="form-field">
                    <input required placeholder="CPF" class="input-field" type="text" name="cpf" id="cpf" maxlength="14">
                </div>

                <div class="form-field">
                    <input required placeholder="Data de Nascimento" class="input-field" type="date" name="dob" id="dob">
                </div>

                <div class="form-field">
                    <input placeholder="Foto" class="input-field" type="file" name="photo" accept="image/*" id="photoInput">
                </div>

                <div id="imagePreview"></div>

                <button class="sendMessage-btn" type="submit">Finalizar Cadastro</button>
            </form>
        </div>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        document.addEventListener("DOMContentLoaded", function() {
            const photoInput = document.getElementById("photoInput");
            const imagePreview = document.getElementById("imagePreview");

            photoInput.addEventListener("change", function() {
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        imagePreview.innerHTML = "";
                        imagePreview.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                } else {
                    imagePreview.innerHTML = "";
                }
            });
        });
    </script>
</body>
</html>
