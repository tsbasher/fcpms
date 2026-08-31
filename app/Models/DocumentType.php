<?php

namespace App\Models;

use App\Helper\ExtendedModel;

class DocumentType extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'project_id',
        'name',
        'description',
        'is_active',
        'created_by',
    ];

    public function bill_documents()
    {
        return $this->hasMany(BillDocument::class, 'document_type_id', 'id');
    }
}
