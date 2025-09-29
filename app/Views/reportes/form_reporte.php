<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte Superhéroes</title>
  <style>
    body { font-family: Arial, sans-serif; display: flex; gap: 20px; padding: 20px; background: #f8f9fa; }
    .config { width: 25%; background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .config h3 { margin-bottom: 10px; font-size: 18px; }
    .config label { display: block; margin: 6px 0; }
    .preview { flex: 1; background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-height: 500px; overflow-y: auto; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table th, table td { border: 1px solid #ddd; padding: 6px; text-align: center; }
    table th { background: #e9ecef; font-weight: bold; }
    .btn { margin-top: 15px; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
  </style>
</head>
<body>

  <!-- Panel Configuración -->
  <div class="config">
    <h3>Configurar Reporte</h3>
    <label>Título del Reporte:
      <input type="text" id="titulo" value="Reporte SH <?= date('Y') ?>" oninput="actualizarVista()">
    </label>

    <!-- ✅ Radios: solo se puede elegir uno -->
    <label><input type="radio" class="filtroGenero" name="genero" value="Male" checked onchange="actualizarVista()"> Masculino</label>
    <label><input type="radio" class="filtroGenero" name="genero" value="Female" onchange="actualizarVista()"> Femenino</label>
    <label><input type="radio" class="filtroGenero" name="genero" value="N/A" onchange="actualizarVista()"> N/A</label>

    <label>Límite de Registros:
      <input type="number" id="limite" value="50" min="10" max="200" onchange="actualizarVista()">
    </label>

    <button class="btn" onclick="generarPDF()">Generar PDF</button>
  </div>

  <!-- Vista Previa -->
  <div class="preview">
    <h3 id="tituloReporte">Reporte SH <?= date('Y') ?></h3>
    <table id="tablaSH">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Género</th>
          <th>Alineación</th>
          <th>Altura</th>
          <th>Peso</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <p id="total"></p>
  </div>

<script>
async function actualizarVista() {
  const titulo = document.getElementById("titulo").value;
  const limite = document.getElementById("limite").value;
  const genero = document.querySelector(".filtroGenero:checked")?.value;

  let formData = new FormData();
  formData.append("titulo", titulo);
  formData.append("limite", limite);
  if (genero) formData.append("genero[]", genero); // lo enviamos igual como array

  const resp = await fetch("<?= base_url('reporte/datos') ?>", {
    method: "POST",
    body: formData
  });
  const data = await resp.json();

  document.getElementById("tituloReporte").innerText = data.titulo;

  const tbody = document.querySelector("#tablaSH tbody");
  tbody.innerHTML = "";
  data.rows.forEach(h => {
    tbody.innerHTML += `
      <tr>
        <td>${h.nombre}</td>
        <td>${h.genero}</td>
        <td>${h.alineacion}</td>
        <td>${h.altura ?? '-'}</td>
        <td>${h.peso ?? '-'}</td>
      </tr>`;
  });

  document.getElementById("total").innerText = `Total: ${data.total} superhéroes`;
}

function generarPDF() {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "<?= base_url('reporte/generar') ?>";

  const titulo = document.getElementById("titulo").value;
  const limite = document.getElementById("limite").value;
  const genero = document.querySelector(".filtroGenero:checked")?.value;

  let inputTitulo = document.createElement("input");
  inputTitulo.type = "hidden";
  inputTitulo.name = "titulo";
  inputTitulo.value = titulo;
  form.appendChild(inputTitulo);

  let inputLimite = document.createElement("input");
  inputLimite.type = "hidden";
  inputLimite.name = "limite";
  inputLimite.value = limite;
  form.appendChild(inputLimite);

  if (genero) {
    let inputGenero = document.createElement("input");
    inputGenero.type = "hidden";
    inputGenero.name = "genero[]"; // sigue siendo array por compatibilidad
    inputGenero.value = genero;
    form.appendChild(inputGenero);
  }

  document.body.appendChild(form);
  form.submit();
}

actualizarVista();
</script>

</body>
</html>
