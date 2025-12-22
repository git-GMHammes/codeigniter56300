<?php
$addDiff = '005';
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <title>Enviar Cliente (POST) — 2 colunas + upload múltiplo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 16px;
    }

    form {
      max-width: 780px;
      margin: 0 auto;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .field {
      margin-bottom: 12px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 4px;
    }

    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="date"],
    input[type="file"] {
      width: 100%;
      padding: 6px;
      box-sizing: border-box;
    }

    .fullwidth {
      grid-column: 1 / -1;
    }

    button {
      padding: 8px 12px;
      margin-top: 8px;
    }

    #fileList {
      margin-top: 8px;
      font-size: 0.95em;
      color: #333;
    }
  </style>
</head>

<body>
  <h1>Enviar Cliente (POST)</h1>

  <form id="customerForm">
    <div class="grid">
      <div class="field">
        <label for="user_id">User ID</label>
        <input id="user_id" name="user_id" type="number" value="5" required>
      </div>

      <div class="field">
        <label for="name">Nome</label>
        <input id="name" name="name" type="text" value="João Braz" required>
      </div>

      <div class="field">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" type="text" value="123.<?= $addDiff ?>.789-05">
      </div>

      <div class="field">
        <label for="whatsapp">WhatsApp</label>
        <input id="whatsapp" name="whatsapp" type="text" value="55219<?= $addDiff ?>89995">
      </div>

      <div class="field">
        <label for="profile">Profile</label>
        <input id="profile" name="profile" type="text" value="Cliente VIP">
      </div>

      <div class="field">
        <label for="mail">Mail</label>
        <input id="mail" name="mail" type="email" value="joao.silva5<?= $addDiff ?>.jr@email.com">
      </div>

      <div class="field">
        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="text" value="(21) <?= $addDiff ?>88-9995">
      </div>

      <div class="field">
        <label for="date_birth">Data de Nascimento</label>
        <input id="date_birth" name="date_birth" type="date" value="1990-05-16">
      </div>

      <div class="field">
        <label for="zip_code">CEP</label>
        <input id="zip_code" name="zip_code" type="text" value="24020-050">
      </div>

      <div class="field">
        <label for="address">Endereço</label>
        <input id="address" name="address" type="text" value="Av. Nova, 456 - Icaraí">
      </div>

      <div class="field">
        <label for="user_id_active">User ID Active (opcional)</label>
        <input id="user_id_active" name="user_id_active" type="number" value="">
      </div>

      <!-- Upload múltiplo - campo com vários arquivos (name="upload_files_path[]") -->
      <div class="field fullwidth">
        <label for="upload_files_path">Anexos (upload múltiplo)</label>
        <input id="upload_files_path" name="upload_files_path[]" type="file" multiple>
        <div id="fileList" aria-live="polite"></div>
      </div>

      <div class="field fullwidth">
        <button type="submit">Enviar (POST)</button>
      </div>
    </div>
  </form>

  <script>
    (function () {
      const apiUrl = 'http://localhost:56300/api/v1/customer-management';
      const form = document.getElementById('customerForm');
      const filesInput = document.getElementById('upload_files_path');
      const fileList = document.getElementById('fileList');

      // Mostrar nomes dos arquivos selecionados
      filesInput.addEventListener('change', () => {
        const files = Array.from(filesInput.files); // Captura corretamente os arquivos selecionados
        if (files.length === 0) {
          fileList.textContent = 'Nenhum arquivo selecionado.';
          return;
        }
        fileList.innerHTML = files.map(f => '- ' + f.name).join('<br>'); // Exibe os nomes dos arquivos
      });

      form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const payload = {
          user_id: form.user_id.value === '' ? null : parseInt(form.user_id.value, 10),
          name: form.name.value,
          cpf: form.cpf.value,
          whatsapp: form.whatsapp.value,
          profile: form.profile.value,
          mail: form.mail.value,
          phone: form.phone.value,
          date_birth: form.date_birth.value,
          zip_code: form.zip_code.value,
          address: form.address.value,
          user_id_active: form.user_id_active.value === '' ? null : parseInt(form.user_id_active.value, 10)
        };

        // Usar FormData para suportar multipart com arquivos
        const formData = new FormData();
        formData.append('payload', JSON.stringify(payload)); // Adiciona os dados como JSON

        // Anexa todos os arquivos (campo name "upload_files_path[]")
        const files = filesInput.files; // Recupera os arquivos diretamente
        for (let i = 0; i < files.length; i++) {
          formData.append('upload_files_path[]', files[i], files[i].name);
        }

        try {
          const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData // Boundary será tratado automaticamente pelo navegador
          });

          const text = await response.text();
          alert('Status: ' + response.status + '\n' + text);
        } catch (err) {
          alert('Erro: ' + (err && err.message ? err.message : err));
        }
      });
    })();
  </script>
</body>

</html>