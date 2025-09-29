<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SuperheroModel;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes/form_reporte');
    }

    public function obtenerDatos()
    {
        $request = service('request');
        $titulo  = $request->getPost('titulo');
        $generos = $request->getPost('genero');  // array
        $limite  = $request->getPost('limite');

        $db = db_connect();
        $builder = $db->table('superhero SH')
            ->select('SH.superhero_name AS nombre, G.gender AS genero, AL.alignment AS alineacion, SH.height_cm AS altura, SH.weight_kg AS peso')
            ->join('gender G', 'SH.gender_id = G.id', 'left')
            ->join('alignment AL', 'SH.alignment_id = AL.id', 'left');

        if (!empty($generos)) {
            $builder->whereIn('G.gender', $generos);
        }

        if (!empty($limite)) {
            $builder->limit($limite);
        }

        $rows = $builder->get()->getResultArray();

        return $this->response->setJSON([
            "titulo" => $titulo,
            "rows"   => $rows,
            "total"  => count($rows)
        ]);
    }

    public function generarPDF()
    {
        $request = service('request');
        $titulo  = $request->getPost('titulo');
        $generos = $request->getPost('genero');
        $limite  = $request->getPost('limite');

        if (!is_array($generos)) {
            $generos = [$generos];
        }

        $db = db_connect();
        $builder = $db->table('superhero SH')
            ->select('SH.superhero_name AS nombre, G.gender AS genero, AL.alignment AS alineacion, SH.height_cm AS altura, SH.weight_kg AS peso')
            ->join('gender G', 'SH.gender_id = G.id', 'left')
            ->join('alignment AL', 'SH.alignment_id = AL.id', 'left');

        if (!empty($generos)) {
            $builder->whereIn('G.gender', $generos);
        }

        if (!empty($limite)) {
            $builder->limit($limite);
        }

        $rows = $builder->get()->getResultArray();

        $data = [
            "titulo" => $titulo,
            "rows"   => $rows,
            "total"  => count($rows)
        ];

        $html = view('reportes/reporte_pdf', $data);

        try {
            $pdf = new Html2Pdf('L', 'A4', 'es', true, 'UTF-8', [10, 10, 10, 10]);
            $pdf->writeHTML($html);
            $pdf->output($titulo . '.pdf');
            exit;
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }

    public function pesos_publishers()
{
    return view('reportes/reporte_pesos_publishers');
}

public function pesos_datos()
{
    $db = db_connect();
    $query = $db->query("SELECT * FROM view_avg_weight_publisher");
    return $this->response->setJSON($query->getResultArray());
}


}
