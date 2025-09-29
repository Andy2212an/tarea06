<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Publishers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f6f9; }
    .sidebar {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-height: 80vh;
      overflow-y: auto; /* Scroll vertical */
    }
    .chart-card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      height: 100%;
    }
    .publisher-label {
      display: flex;
      justify-content: space-between;
      padding: 4px 0;
      border-bottom: 1px dashed #eee;
    }
    .publisher-label:last-child {
      border-bottom: none;
    }
    #graficoPublishers {
      width: 100%;
      height: 100%;
    }
  </style>
</head>
<body>
<div class="container-fluid py-4">
  <div class="row g-4">

    <!-- Panel Configuración -->
    <div class="col-12 col-md-3">
      <div class="sidebar">
        <h5 class="mb-3 text-primary">Configuración</h5>

        <label class="mb-2 fw-bold">Tipo de gráfico:</label>
        <select id="tipoGrafico" class="form-select form-select-sm mb-3" onchange="cambiarTipoGrafico()">
          <option value="bar" selected>Barras</option>
          <option value="pie">Pastel</option>
          <option value="line">Líneas</option>
        </select>

        <label class="mb-2 fw-bold">Publishers:</label>
        <div id="filtrosPublishers" class="mb-3">
          <!-- Aquí se cargan los publishers -->
        </div>
      </div>

      <!-- 🔹 Botón separado del cuadro de configuración -->
      <button class="btn btn-sm btn-outline-primary w-100 mt-3" onclick="actualizarVista()">Actualizar Gráfico</button>
    </div>

    <!-- Vista Previa -->
    <div class="col-12 col-md-9">
      <div class="chart-card d-flex justify-content-center align-items-center">
        <div style="width: 100%; max-width: 800px;">
          <h5 class="mb-3 text-secondary text-center">Superhéroes por Publisher</h5>
          <canvas id="graficoPublishers"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script>
let grafico = null;

// Inicializar gráfico
function renderGraphic(tipo = "bar") {
  const ctx = document.getElementById("graficoPublishers");

  // Ajustar tamaño dinámico según tipo
  if (tipo === "pie") {
    ctx.style.maxWidth = "400px";   // más pequeño
    ctx.style.maxHeight = "400px";
    ctx.parentElement.style.display = "flex";
    ctx.parentElement.style.justifyContent = "center"; // centrado
  } else {
    ctx.style.maxWidth = "100%";   // ocupa todo
    ctx.style.maxHeight = "500px";
    ctx.parentElement.style.display = "block";
  }

  if (grafico) grafico.destroy();

  grafico = new Chart(ctx, {
    type: tipo,
    data: { 
      labels: [], 
      datasets: [{
        label: 'Cantidad de Superhéroes',
        data: [],
        backgroundColor: []
      }] 
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: tipo === "pie" ? "bottom" : "top" },
        title: { display: false }
      },
      scales: (tipo === "bar" || tipo === "line") ? {
        y: { beginAtZero: true, title: { display: true, text: 'Cantidad' } },
        x: { title: { display: true, text: 'Publishers' } }
      } : {}
    }
  });
}

// Generar colores aleatorios
function generarColor() {
  const r = Math.floor(Math.random()*255);
  const g = Math.floor(Math.random()*255);
  const b = Math.floor(Math.random()*255);
  return `rgba(${r},${g},${b},0.7)`;
}

// Cargar publishers
async function cargarFiltros() {
  const resp = await fetch("<?= base_url('public/api/getDataPublishersCache') ?>");
  const data = await resp.json();

  const contenedor = document.getElementById("filtrosPublishers");
  contenedor.innerHTML = "";

  if (data.success) {
    data.resumen.forEach((row, index) => {
      // Solo los 3 primeros estarán marcados al inicio
      const checked = (index < 3) ? "checked" : "";
      contenedor.innerHTML += `
        <label class="publisher-label">
          <span>${row.publisher}</span>
          <input type="checkbox" class="form-check-input filtroPublisher" value="${row.publisher}" ${checked}>
        </label>
      `;
    });
  }
}

// Actualizar gráfico
async function actualizarVista() {
  const resp = await fetch("<?= base_url('public/api/getDataPublishersCache') ?>");
  const data = await resp.json();

  if (data.success) {
    const seleccionados = Array.from(document.querySelectorAll(".filtroPublisher:checked")).map(cb => cb.value);
    const filtrados = data.resumen.filter(row => seleccionados.includes(row.publisher));

    grafico.data.labels = filtrados.map(r => r.publisher);
    grafico.data.datasets[0].data = filtrados.map(r => r.total);
    grafico.data.datasets[0].backgroundColor = filtrados.map(() => generarColor());
    grafico.update();
  }
}

// Cambiar tipo de gráfico
function cambiarTipoGrafico() {
  const tipo = document.getElementById("tipoGrafico").value;
  renderGraphic(tipo);
  actualizarVista();
}

// Inicializar
renderGraphic("bar");
cargarFiltros().then(() => actualizarVista());
</script>

</body>
</html>
