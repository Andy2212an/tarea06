# Proyecto Superhéroes

Este proyecto implementa un sistema en CodeIgniter 4 para la gestión y visualización de datos de **superhéroes** y sus **publishers**, incluyendo reportes en PDF y gráficos interactivos con Chart.js.

---

## ⚙️ Configuración inicial

1. Clonar el repositorio en tu entorno local.
2. Crear la base de datos e importar el archivo SQL:
   ```bash
   app/Database/SQL SUPERHERO
   ```
   Allí se encuentra la estructura y datos iniciales del proyecto.
3. Renombrar el archivo de configuración de entorno:
   ```bash
   mv env .env
   ```
   ⚠️ Nota: Asegúrate de que el archivo se llame exactamente **.env** (con el punto al inicio).
4. Configurar en `.env` la conexión a la base de datos según tus credenciales locales.

---

## 🗄️ Vistas definidas en la base de datos

Este proyecto utiliza dos vistas SQL para simplificar consultas:

### 1. Superhéroes por Publisher
```sql
CREATE VIEW view_superhero_publisher AS
SELECT
    PB.publisher_name AS publisher,
    COUNT(SH.publisher_id) AS total
FROM superhero SH
LEFT JOIN publisher PB ON PB.id = SH.publisher_id
GROUP BY SH.publisher_id, PB.publisher_name;
```

Consulta de prueba:
```sql
SELECT * FROM view_superhero_publisher;
```

---

### 2. Promedio de Pesos por Publisher
```sql
CREATE OR REPLACE VIEW view_avg_weight_publisher AS
SELECT
    PB.publisher_name AS publisher,
    ROUND(AVG(SH.weight_kg),2) AS avg_weight
FROM superhero SH
LEFT JOIN publisher PB ON PB.id = SH.publisher_id
WHERE SH.weight_kg > 0  -- evitamos ceros o nulos
GROUP BY SH.publisher_id, PB.publisher_name
ORDER BY avg_weight ASC;
```

Consulta de prueba:
```sql
SELECT * FROM view_avg_weight_publisher;
```

---

## 🌐 Rutas principales

- 📄 **Reporte PDF de superhéroes:**  
  [http://tarea06.test/reporte](http://tarea06.test/reporte)

- 📊 **Gráfico de Superhéroes por Publisher:**  
  [http://tarea06.test/dashboard/informePublishers](http://tarea06.test/dashboard/informePublishers)

- ⚖️ **Promedio de pesos de superhéroes (ordenado de menor a mayor):**  
  [http://tarea06.test/reporte/pesos_publishers](http://tarea06.test/reporte/pesos_publishers)

---

## 🛠️ Tecnologías usadas

- [CodeIgniter 4](https://codeigniter.com/) – Framework PHP
- [Chart.js](https://www.chartjs.org/) – Librería de gráficos
- MySQL / MariaDB – Base de datos
- Bootstrap 5 – Estilos

---

## 🚀 Autor
Proyecto académico desarrollado por Andy José Uriol Aquije.

