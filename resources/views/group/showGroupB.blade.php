<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel = "stylesheet" href=" ../css/conversa.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <title>document</title>
</head>
<body>
<section class ="">

    <section class = "conversa">


        <div class="top__bar">
            <img src="profile.jpg" alt="Foto do Perfil" class="profile__pic">
            <span class="username">Nome da Pessoa</span>
        </div>

        <section class="conversa__mensagens">

            <div class="message--self">Mensagem
                <span class="hora"> 18:30</span>
            </div>


            <div class="message__other">
                <span class="message__sender">Gabriel</span>
                Mensgem dos outros

                <span class="hora">18:30</span>
            </div>

        </section>

        <form class="campo__mensagem">
            <input type="text" class="input__mensagem" placeholder="Digite uma mensagem" required>
            <input type="file" id="fileInput" style="display: none;" />
            <input type="hidden" id="audioData" />
            <button type="submit" class="button__mensagem">
                <span class="material-symbols-outlined">send</span>
            </button>
            <button type="submit" class="button__file">
                <span class="material-symbols-outlined">attach_file</span>
            </button>
            <button type="submit" class="button__mic">
                <span class="material-symbols-outlined">mic</span>
            </button>
        </form>

    </section>

</section>


<script src="../js/conversa.js"></script>
</body>
</html>