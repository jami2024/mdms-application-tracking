{{-- resources/views/applications/_packaging-details.blade.php --}}

<div class="card border-0 shadow-sm mb-4">

    {{-- Header --}}
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1 fw-semibold text-dark">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    প্যাকেজিং আবেদন বিস্তারিত
                </h5>
                <small class="text-muted">
                    Packaging Application Information
                </small>
            </div>

            @php
                $statusClass = match ($packageApplication->status ?? null) {
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    'submitted' => 'bg-primary',
                    'in_review' => 'bg-warning text-dark',
                    'returned' => 'bg-warning text-dark',
                    'draft' => 'bg-secondary',
                    default => 'bg-secondary',
                };
            @endphp

            <span class="badge {{ $statusClass }} px-3 py-2 fs-6">
                {{ \App\Support\Bengali::label($packageApplication->status ?? 'N/A') }}
            </span>
        </div>
    </div>

    <div class="card-body">

        {{-- Application Information --}}
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-2">
                    <i class="bi bi-file-earmark-text fs-5"></i>
                </div>
                <h6 class="mb-0 fw-semibold">আবেদন সংক্রান্ত তথ্য</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 20%;">আবেদন নম্বর</th>
                            <td><strong
                                    class="text-primary">{{ $packageApplication->package_application_no ?? '—' }}</strong>
                            </td>
                            <th class="bg-light" style="width: 20%;">আবেদনকারীর ID</th>
                            <td>{{ $packageApplication->applicant_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">আবেদন তৈরির তারিখ</th>
                            <td>{{ $packageApplication->created_at ? \Carbon\Carbon::parse($packageApplication->created_at)->format('d M Y, h:i A') : '—' }}
                            </td>
                            <th class="bg-light">সর্বশেষ আপডেট</th>
                            <td>{{ $packageApplication->updated_at ? \Carbon\Carbon::parse($packageApplication->updated_at)->format('d M Y, h:i A') : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">আবেদন স্ট্যাটাস</th>
                            <td><span
                                    class="badge {{ $statusClass }}">{{ \App\Support\Bengali::label($packageApplication->status ?? 'N/A') }}</span>
                            </td>
                            <th class="bg-light">Payment Status</th>
                            <td>
                                @if ($packageApplication->is_payment == 1)
                                    <span class="badge bg-success">পরিশোধিত</span>
                                @else
                                    <span class="badge bg-warning text-dark">অপরিশোধিত</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Device Information --}}
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success bg-opacity-10 text-success rounded p-2 me-2">
                    <i class="bi bi-cpu fs-5"></i>
                </div>
                <h6 class="mb-0 fw-semibold">মেডিকেল ডিভাইসের তথ্য</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 20%;">ডিভাইসের নাম</th>
                            <td colspan="3"><strong>{{ $packageApplication->device_name ?? '—' }}</strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Model No.</th>
                            <td>{{ $packageApplication->model_no ?? '—' }}</td>
                            <th class="bg-light">Registration No.</th>
                            <td>{{ $packageApplication->registration_no ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Manufacturer</th>
                            <td colspan="3">{{ $packageApplication->manufacturer ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Country of Origin</th>
                            <td>{{ $packageApplication->country_of_origin ?? '—' }}</td>
                            <th class="bg-light">Product Grade</th>
                            <td>{{ $packageApplication->product_grade_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Registration Date</th>
                            <td>{{ $packageApplication->registration_date ? \Carbon\Carbon::parse($packageApplication->registration_date)->format('d M Y') : '—' }}
                            </td>
                            <th class="bg-light">Expiry Date</th>
                            <td>{{ $packageApplication->expiry_date ? \Carbon\Carbon::parse($packageApplication->expiry_date)->format('d M Y') : '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Financial Information --}}
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded p-2 me-2">
                    <i class="bi bi-cash-stack fs-5"></i>
                </div>
                <h6 class="mb-0 fw-semibold">আর্থিক তথ্য</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 20%;">আবেদন ফি</th>
                            <td><strong class="text-success">৳
                                    {{ number_format($packageApplication->amount ?? 0, 2) }}</strong></td>
                            <th class="bg-light" style="width: 20%;">Payment Status</th>
                            <td>
                                @if ($packageApplication->is_payment == 1)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Approval Information --}}
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info bg-opacity-10 text-info rounded p-2 me-2">
                    <i class="bi bi-check2-square fs-5"></i>
                </div>
                <h6 class="mb-0 fw-semibold">অনুমোদন সংক্রান্ত তথ্য</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 20%;">Packaging Approval Step</th>
                            <td>{{ $packageApplication->packaging_approval_step ?? 0 }}</td>
                            <th class="bg-light" style="width: 20%;">Dossier Approval Step</th>
                            <td>{{ $packageApplication->dossier_approval_step ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Documents with Thumbnail Preview --}}
        <div>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded p-2 me-2">
                    <i class="bi bi-paperclip fs-5"></i>
                </div>
                <h6 class="mb-0 fw-semibold">সংযুক্ত ডকুমেন্ট</h6>
            </div>

            <div class="row g-3">
                {{-- Packaging Document --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 document-card">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-image text-primary fs-4 me-2"></i>
                            <div>
                                <div class="fw-semibold">Packaging Document</div>
                                <small class="text-muted">প্যাকেজিং ডকুমেন্ট</small>
                            </div>
                        </div>

                        @php
                            $packaging_url = document_url($packageApplication->packaging_path ?? null);
                            $fsc_url = document_url($packageApplication->attested_fsc_path ?? null);
                            $ec_url = document_url($packageApplication->ec_certificate_path ?? null);
                        @endphp

                        @if ($packageApplication->packaging_path)
                            <div class="document-preview">
                                <img src="{{ $packaging_url }}" alt="Packaging Document" class="img-thumbnail document-thumbnail"
                                    alt="Packaging Document" class="img-thumbnail document-thumbnail"
                                    style="width: 100%; max-height: 150px; object-fit: cover; cursor: pointer;"
                                    onclick="openLightbox(this.src, 'Packaging Document')">
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary me-1"
                                    onclick="openLightbox('{{ $packaging_url }}', 'Packaging Document')">
                                    <i class="bi bi-eye me-1"></i> দেখুন
                                </button>
                                <a href="{{ $packaging_url }}" download
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>
                                </a>
                            </div>
                        @else
                            <span class="text-muted small">কোনো ডকুমেন্ট নেই</span>
                        @endif
                    </div>
                </div>

                {{-- FSC Certificate --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 document-card">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-check text-success fs-4 me-2"></i>
                            <div>
                                <div class="fw-semibold">FSC Certificate</div>
                                <small class="text-muted">Attested FSC Certificate</small>
                            </div>
                        </div>

                        @if ($packageApplication->attested_fsc_path)
                            <div class="document-preview">
                                <img src="{{ $fsc_url }}" alt="FSC Certificate" class="img-thumbnail document-thumbnail"
                                    style="width: 100%; max-height: 150px; object-fit: cover; cursor: pointer;"
                                    onclick="openLightbox(this.src, 'FSC Certificate')">
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-success me-1"
                                    onclick="openLightbox('{{ $fsc_url }}', 'FSC Certificate')">
                                    <i class="bi bi-eye me-1"></i> দেখুন
                                </button>
                                <a href="{{ $fsc_url }}" download
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>
                                </a>
                            </div>
                        @else
                            <span class="text-muted small">কোনো ডকুমেন্ট নেই</span>
                        @endif
                    </div>
                </div>

                {{-- EC Certificate --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 document-card">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-check text-warning fs-4 me-2"></i>
                            <div>
                                <div class="fw-semibold">EC Certificate</div>
                                <small class="text-muted">EC Certificate</small>
                            </div>
                        </div>

                        @if ($packageApplication->ec_certificate_path)
                            <div class="document-preview">
                                <img src="{{ $ec_url }}" alt="EC Certificate" class="img-thumbnail document-thumbnail"
                                    style="width: 100%; max-height: 150px; object-fit: cover; cursor: pointer;"
                                    onclick="openLightbox(this.src, 'EC Certificate')">
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-warning me-1"
                                    onclick="openLightbox('{{ $ec_url }}', 'EC Certificate')">
                                    <i class="bi bi-eye me-1"></i> দেখুন
                                </button>
                                <a href="{{ $ec_url }}" download
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>
                                </a>
                            </div>
                        @else
                            <span class="text-muted small">কোনো ডকুমেন্ট নেই</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="documentLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h6 class="modal-title text-white" id="lightboxTitle">Document</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" alt="Document" id="lightboxImage" class="img-fluid"
                    style="max-height: 85vh; width: auto;">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button class="btn btn-outline-light" onclick="downloadLightboxImage()">
                    <i class="bi bi-download me-1"></i> Download
                </button>
                <button class="btn btn-outline-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Lightbox --}}
@push('scripts')
    <script>
        let currentLightboxSrc = '';

        function openLightbox(src, title) {
            currentLightboxSrc = src;
            document.getElementById('lightboxImage').src = src;
            document.getElementById('lightboxTitle').textContent = title || 'Document';
            new bootstrap.Modal(document.getElementById('documentLightbox')).show();
        }

        function downloadLightboxImage() {
            if (currentLightboxSrc) {
                const link = document.createElement('a');
                link.href = currentLightboxSrc;
                link.download = currentLightboxSrc.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        // Close lightbox on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('documentLightbox'));
                if (modal) modal.hide();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .document-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .document-thumbnail {
            transition: opacity 0.2s ease;
        }

        .document-thumbnail:hover {
            opacity: 0.8;
        }

        .modal-xl {
            max-width: 90vw;
        }
    </style>
@endpush
