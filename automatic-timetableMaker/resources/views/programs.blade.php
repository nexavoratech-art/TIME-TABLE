@extends('layouts.app')
@section('title', 'Programs')

@section('content')
<div class="row g-4">
    <div class="col-md-7 mx-auto">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="card-title mb-3"><i class="bi bi-diagram-3-fill text-success me-2"></i>Add Program</h5>
            
            <!-- Input Form -->
            <form id="programForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Department</label>
                    <input type="text" class="form-control" id="input_dept_id" placeholder="e.g. School of Computing" required>

                   
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Program Name</label>
                    <input type="text" class="form-control" id="input_program_name" placeholder="e.g. BSc Computer Science" required>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="openReviewModal()">
                    <i class="bi bi-eye me-1"></i>Review Details
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Blurred Backdrop Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-shield-check me-2 text-info"></i>Confirm Program Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('programs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">Please review or edit your inputs before saving to the database.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Department ID</label>
                        <input type="text" class="form-control" name="dept_id" id="review_dept_id" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Program Name</label>
                        <input type="text" class="form-control" name="program_name" id="review_program_name" required>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Edit Inputs</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Confirm & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReviewModal() {
    const dept = document.getElementById('input_dept_id').value;
    const prog = document.getElementById('input_program_name').value;

    if (!prog) {
        alert('Please complete the form fields first.');
        return;
    }

    document.getElementById('review_dept_id').value = dept;
    document.getElementById('review_program_name').value = prog;

    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
}
</script>
@endsection