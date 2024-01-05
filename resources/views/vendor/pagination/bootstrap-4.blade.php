@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="" style="color: grey; text-decoration: none; font-size: 2rem"  aria-hidden="true"><i class="bi-arrow-bar-left"></i></span>
                </li>
            @else
                <li class="">
                    <a class="" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="color: #6610f2; text-decoration: none; font-size: 2rem"><i class="bi-arrow-bar-left"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="my-button btn btn-sm g-2 h-4 m-1 text-white" style="font-size: 1.4rem" aria-current="page"><span class="">{{ $page }}</span></li>
                        @else
                            <li class="btn btn-sm g-2 h-4 m-1"><a class="" style="color: #6610f2; text-decoration: none; font-size: 1.2rem" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="" style="color: #6610f2; text-decoration: none; font-size: 2rem" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i class="bi-arrow-bar-right"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="" style="color: grey; text-decoration: none; font-size: 2rem" aria-hidden="true"><i class="bi-arrow-bar-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
