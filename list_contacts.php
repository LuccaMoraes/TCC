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
            left: 20px; /* Muda para a esquerda */
            font-size: 24px;
            cursor: pointer;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0; /* Muda para a esquerda */
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

        h1 {
            margin: 0;
            font-size: 2em;
        }

        input[type="text"],
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #ad5389;
            background: linear-gradient(
                0deg,
                rgba(77, 54, 208, 1) 0%,
                rgba(132, 116, 254, 1) 100%
            );
			color: white;
			border: none;
			padding: 12px 20px;
			cursor: pointer;
			border-radius: 8px;
			font-size: 18px;
			transition: background-color 0.3s, transform 0.2s;
			margin-left: 155px;
        }

        button:hover {
            box-shadow: 0 0.5em 1.5em -0.5em #4d36d0be;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        h2 {
            margin-top: 20px;
            font-size: 1.5em;
            text-align: center;
            color: #fff;
        }

        ul {
            list-style-type: none;
            padding: 0;
            max-width: 800px;
            margin: 0 auto;
        }

        li {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            margin-left: 500px;
            margin-right: 500px;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        li:hover {
            background-color: rgba(240, 240, 240, 0.9);
        }

        img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-details {
            display: flex;
            flex-direction: column;
        }

        .contact-details span {
            margin-bottom: 5px;
            font-size: 1em;
        }

        #imagePreview {
            text-align: center;
            margin-bottom: 15px;
        }

        .cadastro {
            background-color: #0a192f;
            padding: 20px;
            margin: 20px auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #ccc;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
        }

        input[type="text"],
        input[type="file"],
        input[type="date"] {
            width: calc(100%);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <header>
        <h1>Alunos:</h1>
        <span class="navbar-toggle" onclick="openNav()">
            <i class="fas fa-bars"></i>
        </span>
    </header>

    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="#">Home</a>
        <a href="http://localhost/TCC%20v7/">Cadastro</a>
        <a href="http://localhost/TCC%20v7/list_contacts.php">Lista de Alunos</a>
        <a href="http://127.0.0.1:5000/">Captura Facial</a>
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

<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "banco_tcc";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$sql = "SELECT id, nome, telefone, foto, Data, CPF FROM contatos"; // Inclui o campo 'id' para referência
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($row['foto']) . "' alt='Foto'>";
        echo "<div class='contact-details'>";
        echo "<span><strong>Nome:</strong> " . htmlspecialchars($row['nome']) . "</span>";
        echo "<span><strong>Telefone:</strong> " . htmlspecialchars($row['telefone']) . "</span>";
        echo "<span><strong>Data:</strong> " . htmlspecialchars($row['Data']) . "</span>";
        echo "<span><strong>CPF:</strong> " . htmlspecialchars($row['CPF']) . "</span>";
        echo "</div>";

        // Botão para Editar
        echo "<form action='editar.php' method='GET' style='display:inline-block;'>";
        echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<button type='submit'>Editar</button>";
        echo "</form>";

        // Botão para Deletar
        echo "<form action='deletar.php' method='POST' style='display:inline-block;'>";
        echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<button type='submit' onclick='return confirm(\"Tem certeza que deseja deletar este contato?\")'>Deletar</button>";
        echo "</form>";

        echo "</li>";
    }
} else {
    echo "<li>Nenhum contato encontrado.</li>";
}

$conn->close();
?>