<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CertificateRepositoryInterface
{
    public function create(Request $request);
}