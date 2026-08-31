<?php

namespace App\Models;

use App\Helper\ExtendedModel;

class BillDocument extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'bill_id',
        'project_id',
        'document_type_id',
        'title',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_by',
        'remarks',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }
}
