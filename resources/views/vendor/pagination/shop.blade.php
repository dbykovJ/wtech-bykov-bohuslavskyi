@if ($paginator->hasPages())
    <nav class="shop-pagination" role="navigation" aria-label="Пагінація магазину">
        <ul class="shop-pagination__list">
            @if ($paginator->onFirstPage())
                <li>
                    <span class="pagination__btn pagination__btn--disabled" aria-disabled="true" aria-label="Попередня сторінка">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a class="pagination__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Попередня сторінка">&lsaquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pagination__dots">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="pagination__btn active" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a class="pagination__btn" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a class="pagination__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Наступна сторінка">&rsaquo;</a>
                </li>
            @else
                <li>
                    <span class="pagination__btn pagination__btn--disabled" aria-disabled="true" aria-label="Наступна сторінка">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

