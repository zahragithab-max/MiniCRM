<?php

namespace App\Modules\Documents\Services;

use App\Modules\Documents\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function upload(
        Model $documentable,
        UploadedFile $file,
        ?string $category = null
    ): Document {
        $version = $documentable->documents()->max('version') + 1;

        $path = $file->store(
            'documents/' . $documentable->getTable() . '/' . $documentable->getKey(),
            'public'
        );

        return $documentable->documents()->create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'category' => $category,
            'version' => $version,
        ]);
    }

    public function download(Document $document): string
    {
        return Storage::disk('public')->path($document->file_path);
    }

    public function copy(Document $document): Document
    {
        $newPath = 'documents/copies/' . uniqid() . '_' . $document->name;

        Storage::disk('public')->copy(
            $document->file_path,
            $newPath
        );

        return $document->documentable->documents()->create([
            'name' => $document->name,
            'file_path' => $newPath,
            'mime_type' => $document->mime_type,
            'file_size' => $document->file_size,
            'category' => $document->category,
            'version' => $document->version + 1,
        ]);
    }
}