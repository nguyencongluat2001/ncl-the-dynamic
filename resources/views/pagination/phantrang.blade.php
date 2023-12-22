<style>
    .a {
        color:red;
    }
</style>
<input type="hidden" name="_currentPage" id="_currentPage" value="{{$paginator->currentPage()}}">
<div class="row">
    <div class="col-sm-3">
        <div class="dataTables_info"><span class="page-link">Có {{$paginator->count()}}/ {{$paginator->total()}} bản ghi</span></div>
    </div>
    <div class="col-sm-6">
        <div class="main_paginate" >
            @if ($paginator->hasPages())
            <ul class="pagination pagination-sm" style="margin: 0;white-space: nowrap;text-align: center;display: flex;justify-content: center;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link" style="color:black"><i class="fas fa-arrow-alt-circle-left fa-2x" style="color: steelblue;"></i></span></li>
                @else
                   <li class="page-item"><a page="{{$paginator->currentPage() - 1}}" class="page-link" style="color:black !important" rel="prev"><i class="fas fa-arrow-alt-circle-left fa-2x" style="color: steelblue;"></i></a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <li class="page-item disabled"><span class="page-link" style="color:black !important">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item active"><span class="page-link" style="color:black !important;background-color:#6edf64">{{ $page }}</span></li>
                @else
                <li class="page-item"><a page="{{ $page }}" class="page-link" style="color:black !important">{{ $page }}</a></li>
                @endif
                @endforeach
                @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <li class="page-item"><a page="{{$paginator->currentPage() + 1}}" class="page-link" style="color:black !important" rel="next"><i class="fas fa-arrow-alt-circle-right fa-2x" style="color: steelblue;"></i></a></li>
                @else
                <li class="page-item disabled"><span class="page-link" style="color:black"><i class="fas fa-arrow-alt-circle-right fa-2x" style="color: steelblue;"></i></span></li>
                @endif
            </ul>
            @else
            <ul class="pagination pagination-sm" style="margin: 0;white-space: nowrap;text-align: center;display: flex;justify-content: center;">
                <li class="page-item disabled"><span class="page-link" style="color:black !important"><i class="fas fa-arrow-alt-circle-left fa-2x" style="color: steelblue;"></i></i></span></li>
                <li class="page-item active"><span class="page-link" style="color:black">1</span></li>
                <li class="page-item disabled"><span class="page-link" style="color:black"><i class="fas fa-arrow-alt-circle-right fa-2x" style="color: steelblue;"></i></span></li>
            </ul>
            @endif
        </div>
    </div>
    
    <div class="col-sm-2">
            <select id="cbo_nuber_record_page" class="col-sm-6 form-control input-sm" name="cbo_nuber_record_page">
                <option id="15" name="15" value="15">15</option>
                <option id="30" name="30" value="30">30</option>
                <option id="50" name="50" value="50">50</option>
            </select>
        </div>
</div>