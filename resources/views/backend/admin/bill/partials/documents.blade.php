<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Uploaded Documents</h3>
    </div>
    <div class="card-body">
        @if ($documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-head-fixed" id="admin-document-table">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Document Type</th>
                            <th>Title / File</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $document->document_type->name ?? 'N/A' }}</td>
                                <td>{{ $document->title ?: $document->original_name }}
                                    <small class="d-block text-muted">{{ $document->original_name }} ({{ round($document->file_size / 1024, 2) }} KB)</small>
                                </td>
                                <td>{{ $document->created_at ? $document->created_at->format('d M Y h:i A') : '-' }}</td>
                                <td>
                                    @php
                                        $document_url = Storage::url($document->file_path);
                                        $previewable = str_starts_with($document->mime_type, 'image/') || in_array($document->mime_type, ['application/pdf', 'application/x-pdf']);
                                    @endphp
                                    @if ($previewable)
                                        <button type="button" class="btn btn-sm btn-primary view_document" data-type="{{ $document->mime_type }}" data-url="{{ $document_url }}" data-title="{{ $document->title ?: $document->original_name }}"><i class="fa fa-eye"></i> View</button>
                                    @endif
                                    <a href="{{ $document_url }}" download="{{ $document->original_name }}" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No documents uploaded for this bill yet.</p>
        @endif
    </div>
</div>