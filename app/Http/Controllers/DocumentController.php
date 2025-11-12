<?php

namespace App\Http\Controllers;

use App\Support\Paths;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentController
{
    public function __construct(
        private Paths $paths,
    ) {
    }

    public function download(Request $request)
    {
        $documentId = $request->query->get('id');
        $publicFilePath = '/public/uploads/' . $documentId;
        $filePath = $this->paths->base . $publicFilePath;

        if (!file_exists($filePath)) {
            throw new FileException('The requested file does not exist');
        }

        $name = $request->query->get('name', $documentId);
        $type = $request->query->get('type', 'application/octet-stream');

        return new Response(content: file_get_contents($filePath), headers: [
            'X-Sendfile' => $publicFilePath,
            'Content-Type' => $type,
            'Content-Disposition' => "attachment; filename=$name",
            'Content-Length' => filesize($filePath),
        ]);
    }
}
