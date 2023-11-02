<!-- Bootstrap 5 -->
<input type="hidden" name="_currentPage" id="_currentPage" value="{{ $paginator->currentPage() }}">

<div class="row">
    <div class="col-sm-3 col-md-3">
        <span>
            {{ Lang::get('System::index.page_list') }}
            {{ $paginator->count() }}/{{ $paginator->total() }} Bản ghi
        </span>
    </div>

    <div class="col-sm-6 col-md-6">
        <nav class="main_paginate">
            @if ($paginator->hasPages())
            <ul class="pagination pagination-success pagination-sm" style="justify-content: center;">
                <!-- First Page -->
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="First Page" page="1">
                        <i class="fas fa-fast-backward"></i>
                    </a>
                </li>

                <!-- Previous page -->
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="Previous Page" page="{{ $paginator->currentPage() - 1 }}">
                        <i class="fas fa-step-backward"></i>
                    </a>
                </li>

                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                <a class="page-link" page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endforeach
                    @endif
                @endforeach

                <!-- Next Page -->
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" aria-label="Next Page" page="{{ $paginator->currentPage() + 1 }}">
                        <i class="fas fa-step-forward"></i>
                    </a>
                </li>

                <!-- Last Page -->
                <li class="page-item {{ $paginator->lastPage() === $paginator->currentPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="Last page" page="{{ $paginator->lastPage() }}">
                        <i class="fas fa-fast-forward"></i>
                    </a>
                </li>
            </ul>
            @else
            <ul class="pagination pagination-success pagination-sm" style="justify-content: center;">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="First Page" page="1">
                        <i class="fas fa-fast-backward"></i>
                    </a>
                </li>
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="Previous Page" page="{{ $paginator->currentPage() - 1 }}">
                        <i class="fas fa-step-backward"></i>
                    </a>
                </li>
                <li class="page-item disabled active"><span class="page-link">1</span></li>
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" aria-label="Next Page" page="{{ $paginator->currentPage() + 1 }}">
                        <i class="fas fa-step-forward"></i>
                    </a>
                </li>
                <li class="page-item {{ $paginator->lastPage() === $paginator->currentPage() ? 'disabled' : '' }}">
                    <a class="page-link" aria-label="Last page" page="{{ $paginator->lastPage() }}">
                        <i class="fas fa-fast-forward"></i>
                    </a>
                </li>
            </ul>
            @endif
        </nav>
    </div>

    <div class="col-sm-3 col-md-3">
        <div class="row left_paginate">
            <span for="cbo_nuber_record_page" class="col-sm-7 text-end">Hiển thị</span>
            <div class="col-sm-5 float-end">
                <select id="cbo_nuber_record_page" class="form-control form-select" name="cbo_nuber_record_page">
                    <option id="15" name="15" value="15">15</option>
                    <option id="50" name="50" value="50">50</option>
                    <option id="100" name="100" value="100">100</option>
                </select>
            </div>
        </div>
    </div>
</div>
