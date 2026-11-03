@extends('adminlte::page')
@section('title', 'User Verification / Agreements')

@section('content_header')
@stop

@section('content')
<div class="container-fluid mb-3 pt-3">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">📋 User Verification & Agreements</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('agreements.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Send New Agreement
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="agreements-table">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Agreement Type</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Signed At</th>
                    <th>Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agreements as $ag)
                <tr>
                    <td>{{ $ag->id }}</td>
                    <td><strong>{{ $ag->user->name ?? 'N/A' }}</strong></td>
                    <td>{{ $ag->user->email ?? 'N/A' }}</td>
                    <td>
                        @php
                            $types = ['salary'=>'💰 Salary','commission'=>'🤝 Commission','channel_partner'=>'🔵 Channel Partner'];
                        @endphp
                        <span class="badge badge-secondary p-2">{{ $types[$ag->agreement_type] ?? $ag->agreement_type }}</span>
                    </td>
                    <td>
                        @if($ag->status === 'signed')
                            <span class="badge badge-success p-2"><i class="fas fa-check"></i> Signed</span>
                        @elseif($ag->status === 'expired')
                            <span class="badge badge-danger p-2"><i class="fas fa-times"></i> Expired</span>
                        @else
                            <span class="badge badge-warning p-2"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    </td>
                    <td>{{ $ag->created_at->format('d M Y, h:i A') }}</td>
                    <td>{{ $ag->signed_at ? $ag->signed_at->format('d M Y, h:i A') : '—' }}</td>

                    {{-- Document Column --}}
                    <td>
                        <div class="d-flex align-items-center" style="gap: 4px;">
                            @if($ag->uploaded_document)
                                <a href="{{ route('agreements.document.view', $ag->id) }}" class="btn btn-sm btn-secondary" target="_blank" title="View Attached File (Sent: {{ $ag->document_sent_at ? $ag->document_sent_at->format('d M, h:i A') : 'N/A' }})" data-toggle="tooltip">
                                    <i class="fas fa-paperclip"></i>
                                </a>
                            @else
                                <span class="text-muted mr-1" style="font-size:14px;">—</span>
                            @endif
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#uploadModal{{ $ag->id }}" title="Upload & Send Attachment" data-toggle="tooltip">
                                <i class="fas fa-upload"></i>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex align-items-center" style="gap: 4px;">
                            @if($ag->status !== 'signed')
                            <form method="POST" action="{{ route('agreements.resend', $ag->id) }}" class="m-0">
                                @csrf
                                <button class="btn btn-sm btn-primary" onclick="return confirm('Resend agreement?')" title="Resend Agreement" data-toggle="tooltip">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                            @else
                                <span class="text-success" title="Completed" data-toggle="tooltip" style="font-size: 14px; margin-right:4px;">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <a href="{{ route('agreements.print', $ag->id) }}" class="btn btn-sm btn-info" target="_blank" title="View Generated Document" data-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="downloadSilentPdf({{ $ag->id }}, '{{ addslashes($ag->user->name ?? 'User') }}')" class="btn btn-sm btn-success" title="Download Document PDF" data-toggle="tooltip">
                                    <i class="fas fa-download"></i>
                                </button>
                            @endif

                            {{-- Delete Button (always visible) --}}
                            <form method="POST" action="{{ route('agreements.destroy', $ag->id) }}" class="m-0"
                                  onsubmit="return confirm('Are you sure you want to delete this agreement? This cannot be undone.')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Agreement" data-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Upload Modal for each row --}}
                <div class="modal fade" id="uploadModal{{ $ag->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-upload mr-2"></i>Upload Document — {{ $ag->user->name ?? 'User' }}</h5>
                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <form action="{{ route('agreements.document.upload', $ag->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label><i class="fas fa-envelope mr-1"></i> Send To Email <span class="text-danger">*</span></label>
                                        <input type="email" name="notify_email" class="form-control"
                                               value="{{ $ag->user->email ?? '' }}" required
                                               placeholder="Enter email address">
                                        <small class="text-muted">Document will be emailed to this address</small>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-file mr-1"></i> Select Document <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="document" id="docFile{{ $ag->id }}"
                                                   accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                            <label class="custom-file-label" for="docFile{{ $ag->id }}">Choose file...</label>
                                        </div>
                                        <small class="text-muted">Allowed: PDF, DOC, DOCX, PNG, JPG (Max 10MB)</small>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-sticky-note mr-1"></i> Note / Message (Optional)</label>
                                        <textarea name="upload_note" class="form-control" rows="3"
                                                  placeholder="e.g. Please review the terms and conditions attached...">{{ $ag->upload_note }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i> Upload & Send Email
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">No agreements sent yet. Click "Send New Agreement" to get started.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $agreements->links() }}</div>
</div>
@stop

@section('css')
<style>
.badge { font-size: 12px; }
.btn-xs { padding: 2px 7px; font-size: 11px; border-radius: 4px; }
.dt-search { display: none; }
.dt-layout-cell { overflow-x: auto; }
.dt-buttons { float: right; margin-bottom: 10px; }
.dt-buttons .dt-button {
    background-color: #000 !important;
    color: #fff !important;
    padding: 5px 12px !important;
    border-radius: 4px !important;
    border: none !important;
    transition: all 0.3s !important;
}
.dt-buttons .dt-button:hover {
    background-color: #333 !important;
    transform: translateY(-1px);
}
.filters input {
    width: 100%;
    padding: 3px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.filters th {
    padding: 8px 5px !important;
    background-color: #f8f9fa !important;
}
</style>
@stop

@section('js')
<script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
// Initialize Tooltips
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).ready(function() {
    $('#agreements-table thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#agreements-table thead');

    var table = $('#agreements-table').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [[0, 'desc']], // Maintain latest on top
        layout: {
            topStart: {
                buttons: ['csv', 'excel', 'pdf', 'print']
            }
        },
        initComplete: function() {
            var api = this.api();
            api.columns().every(function() {
                var column = this;
                var index = column.index();
                var cell = $('.filters th').eq(index);
                var title = $(column.header()).text().trim();
                
                if (title.toLowerCase() !== 'actions' && title.toLowerCase() !== 'document') {
                    var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                    cell.html(input);

                    $(input).on('keyup change clear', function() {
                        var val = this.value;
                        if (title.toLowerCase() === 'user' || title.toLowerCase() === 'username') {
                            val = $.fn.dataTable.util.escapeRegex(val);
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        } else {
                            if (column.search() !== val) {
                                column.search(val).draw();
                            }
                        }
                    });
                } else {
                    cell.html('');
                }
            });
        }
    });
});


// Show filename in custom file input
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file...';
        var label = e.target.nextElementSibling;
        label.textContent = fileName;
    });
});

// Silent Backend PDF Download using JS
function downloadSilentPdf(id, userName) {
    var originalBtnHtml = event.currentTarget.innerHTML;
    var btn = event.currentTarget;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    // Fetch the raw print blade output
    fetch("{{ url('/agreements') }}/" + id + "/print")
    .then(response => response.text())
    .then(html => {
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        var docContainer = tempDiv.querySelector('.document-container');
        var styleTags = tempDiv.querySelectorAll('style');
        
        var renderWrapper = document.createElement('div');
        // Place wrapper out of normal flow but still visible to canvas
        renderWrapper.style.position = 'absolute';
        renderWrapper.style.top = '0px';
        renderWrapper.style.left = '0px';
        renderWrapper.style.zIndex = '-9999';
        renderWrapper.style.background = '#fff';

        // Add the CSS styles
        styleTags.forEach(style => renderWrapper.appendChild(style.cloneNode(true)));
        
        // Apply strict print layout to exactly match A4 PDF
        docContainer.style.margin = '0';
        docContainer.style.boxShadow = 'none';
        docContainer.style.padding = '15mm 20mm';
        docContainer.style.minHeight = 'auto'; // Fix: Prevents 1px overflow causing a blank 2nd page
        docContainer.style.overflow = 'hidden';
        
        renderWrapper.appendChild(docContainer);
        document.body.appendChild(renderWrapper);
        
        var opt = {
          margin:       0,
          filename:     'Agreement_' + userName + '_' + id + '.pdf',
          image:        { type: 'jpeg', quality: 1.0 },
          html2canvas:  { scale: 2, useCORS: true },
          jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        // Generate and download after a brief delay for styles to compute
        setTimeout(() => {
            html2pdf().set(opt).from(docContainer).save().then(function() {
                document.body.removeChild(renderWrapper);
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
            });
        }, 500);
    })
    .catch(err => {
        console.error(err);
        btn.innerHTML = originalBtnHtml;
        btn.disabled = false;
        alert('Failed to generate PDF. Please use the View button.');
    });
}
</script>
@stop
