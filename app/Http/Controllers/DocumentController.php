<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentController
{
    public function __construct(
        private Request $request,
    ) {}

    public function download()
    {
        # Нет валидации, ULTRA HUYOVA!

        $documentId = $this->request->query->get('id');
        $publicFilePath = '/public/uploads/'.$documentId;
        $filePath = BASE_DIR.$publicFilePath;

        if (!file_exists($filePath)) {
            throw new FileException('The requested file does not exist');
        }

        $name = $this->request->query->get('name', $documentId);
        $type = $this->request->query->get('type', 'application/octet-stream');

        return new Response(content: file_get_contents($filePath), headers: [
            'X-Sendfile' => $publicFilePath,
            'Content-Type' => $type,
            'Content-Disposition' => "attachment; filename=$name",
            'Content-Length' => filesize($filePath),
        ]);
    }
}
