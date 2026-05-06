@if ($paginator->hasPages())
<nav class="pagination" aria-label="Navigation entre les pages">
    <ul class="pagination__list">

        {{-- Précédent --}}
        @if ($paginator->onFirstPage())
            <li class="pagination__item pagination__item--disabled" aria-disabled="true">
                <span class="pagination__link">‹</span>
            </li>
        @else
            <li class="pagination__item">
                <a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente">‹</a>
            </li>
        @endif

        {{-- Numéros de pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="pagination__item pagination__item--dots" aria-hidden="true"><span class="pagination__link">…</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination__item pagination__item--active" aria-current="page">
                            <span class="pagination__link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="pagination__item">
                            <a class="pagination__link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Suivant --}}
        @if ($paginator->hasMorePages())
            <li class="pagination__item">
                <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante">›</a>
            </li>
        @else
            <li class="pagination__item pagination__item--disabled" aria-disabled="true">
                <span class="pagination__link">›</span>
            </li>
        @endif

    </ul>
</nav>
@endif
