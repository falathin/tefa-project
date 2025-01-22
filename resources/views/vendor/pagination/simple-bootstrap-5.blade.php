@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="d-flex justify-content-between align-items-center">
        <ul class="pagination mb-0">
            {{-- Tombol Halaman Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&laquo; Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
                </li>
            @endif

            {{-- Tombol Halaman Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Berikutnya &raquo;</span>
                </li>
            @endif
        </ul>

        {{-- Informasi Halaman Saat Ini --}}
        <span class="text-muted small">
            &nbsp;&nbsp;&nbsp;Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </span>
    </nav>
@endif