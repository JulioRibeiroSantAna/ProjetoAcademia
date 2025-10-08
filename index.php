<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Calculadora de Massa Atômica</title>
</head>
<body>
  <h2>Calculadora de Massa Atômica</h2>

  <label>Quantidade de isótopos: </label>
  <input type="number" id="numIsotopos" min="1">
  <button onclick="criarCampos()">Gerar Campos</button>

  <form id="formIsotopos"></form>
  <button onclick="calcularMassa()">Calcular Massa Atômica</button>

  <h3 id="resultado"></h3>

  <script>
    function criarCampos() {
      const num = document.getElementById("numIsotopos").value;
      const form = document.getElementById("formIsotopos");
      form.innerHTML = ""; // limpa antes de gerar

      for (let i = 1; i <= num; i++) {
        form.innerHTML += `
          <p>Isótopo ${i}:</p>
          Massa (u): <input type="number" id="massa${i}" step="0.01">
          Abundância (%): <input type="number" id="abundancia${i}" step="0.01"><br><br>
        `;
      }
    }

    function calcularMassa() {
      const num = document.getElementById("numIsotopos").value;
      let soma = 0;

      for (let i = 1; i <= num; i++) {
        let massa = parseFloat(document.getElementById(`massa${i}`).value);
        let abundancia = parseFloat(document.getElementById(`abundancia${i}`).value);
        soma += massa * abundancia;
      }

      let resultado = soma / 100;
      document.getElementById("resultado").innerText = 
        "Massa Atômica = " + resultado.toFixed(2) + " u";
    }
  </script>
</body>
</html>
