<?php

namespace App\Http\Controllers;

use App\Repositories\PictureRepository;
use App\Support\Paths;
use Symfony\Component\HttpFoundation\Response;

class PictureController
{
    public function __construct(
        private Paths $paths,
        private PictureRepository $pictureRepository,
    ) {
    }

    public function download(int $id)
    {
        $path = $this->paths->upload.'/pictures/'.$id;

        if (!file_exists($path)) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $picture = $this->pictureRepository->find($id);

        return new Response(content: file_get_contents($path), headers: [
            'Content-Type' => $picture->mime,
            'Content-Disposition' => "attachment; filename=$picture->name",
            'Content-Length' => filesize($path),
        ]);
    }
}
