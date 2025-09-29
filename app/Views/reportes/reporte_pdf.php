<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h2 { text-align: center; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table th, table td { border: 1px solid #333; padding: 5px; text-align: center; }
    table th { background: #eee; }
    p { margin-top: 10px; }
  </style>
</head>
<body>
  <h2><?= esc($titulo) ?></h2>
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Género</th>
        <th>Alineación</th>
        <th>Altura</th>
        <th>Peso</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['nombre']) ?></td>
          <td><?= esc($r['genero']) ?></td>
          <td><?= esc($r['alineacion']) ?></td>
          <td><?= esc($r['altura'] ?? '-') ?></td>
          <td><?= esc($r['peso'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p>Total: <?= $total ?> superhéroes</p>
</body>
</html>
