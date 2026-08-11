<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
    <div class="toast show align-items-center text-bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fs-6">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div class="toast show align-items-center text-bg-danger border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fs-6">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') ?? 'Please check the form for errors.' }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>