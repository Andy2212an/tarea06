<?php

namespace App\Controllers;

class PDFController extends BaseController
{
    public function index(): string
    {
        return view('pdf/interfaz');
    }
}
