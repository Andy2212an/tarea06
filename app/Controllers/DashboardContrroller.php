<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ReportePublisher;

class DashboardContrroller extends BaseController
{
    public function getInformePublishers()
    {
        return view('dashboard/informe_publishers');
    }

    public function getDataPublishersCache()
    {
        $this->response->setContentType("application/json");

        $cacheKey = 'resumenPublishers';
        $data = cache($cacheKey);

        if ($data == null) {
            $reporte = new ReportePublisher();
            $data = $reporte->findAll();
            cache()->save($cacheKey, $data, 3600); // 1 hora en caché
        }

        if (!$data) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No encontramos publishers',
                'resumen' => []
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Publishers',
            'resumen' => $data
        ]);
    }
}
