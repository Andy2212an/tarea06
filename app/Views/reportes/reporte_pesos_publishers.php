<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Promedio Pesos SH por Publisher</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f6f9; }
    .sidebar {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-height: 80vh;
      overflow-y: auto;
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
    #graficoPesos {
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

        <label class="mb-2 fw-bold">Publishers:</label>
        <div id="filtrosPublishers" class="mb-3">
          <!-- Se cargan dinámicamente -->
        </div>
      </div>

      <!-- Botón separado -->
      <button class="btn btn-sm btn-outline-primary w-100 mt-3" onclick="actualizarVista()">Actualizar Gráfico</button>
    </div>

    <!-- Vista Previa -->
    <div class="col-12 col-md-9">
      <div class="chart-card d-flex justify-content-center align-items-center">
        <div style="width: 100%; max-width: 800px;">
          <h5 class="mb-3 text-secondary text-center">Promedio de Pesos por Publisher</h5>
          <canvas id="graficoPesos"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
<script>
let grafico = null;

// Inicializar gráfico (línea fija)
function renderGraphic() {
  const ctx = document.getElementById("graficoPesos");

  ctx.style.maxWidth = "100%";
  ctx.style.maxHeight = "500px";
  ctx.parentElement.style.display = "block";

  if (grafico) grafico.destroy();

  grafico = new Chart(ctx, {
    type: "line",  // siempre línea
    data: { 
      labels: [], 
      datasets: [{
        label: 'Promedio Peso (kg)',
        data: [],
        borderColor: "rgba(54,162,235,0.9)",
        backgroundColor: "rgba(54,162,235,0.3)",
        fill: true,
        tension: 0.3
      }] 
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: "top" },
        tooltip: { callbacks: {
          label: function(context) {
            return context.raw + " kg";
          }
        }}
      },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Peso (kg)' } },
        x: { title: { display: true, text: 'Publishers' } }
      }
    }
  });
}

// Cargar publishers
async function cargarFiltros() {
  const resp = await fetch("<?= base_url('reporte/pesos_datos') ?>");
  const data = await resp.json();

  const contenedor = document.getElementById("filtrosPublishers");
  contenedor.innerHTML = "";

  data.forEach((row, index) => {
    const checked = (index < 3) ? "checked" : ""; //  primeros 3 seleccionados
    contenedor.innerHTML += `
      <label class="publisher-label">
        <span>${row.publisher}</span>
        <input type="checkbox" class="form-check-input filtroPublisher" value="${row.publisher}" ${checked}>
      </label>
    `;
  });
}

// Actualizar gráfico
async function actualizarVista() {
  const resp = await fetch("<?= base_url('reporte/pesos_datos') ?>");
  const data = await resp.json();

  const seleccionados = Array.from(document.querySelectorAll(".filtroPublisher:checked")).map(cb => cb.value);
  const filtrados = data.filter(row => seleccionados.includes(row.publisher));

  grafico.data.labels = filtrados.map(r => r.publisher);
  grafico.data.datasets[0].data = filtrados.map(r => r.avg_weight);
  grafico.update();
}

// Inicializar
renderGraphic();
cargarFiltros().then(() => actualizarVista());
</script>

</body>
</html>
