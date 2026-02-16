@if (!empty($breadcrumbs))
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex align-items-center">

                <nav aria-label="breadcrumb" class="w-100">
                    <ol class="breadcrumb mb-3 align-items-center">

                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!empty($breadcrumb['route']))
                                <li class="breadcrumb-item">
                                    <a href="{{ is_array($breadcrumb['route'])
                                        ? route($breadcrumb['route'][0], $breadcrumb['route'][1])
                                        : route($breadcrumb['route']) }}"
                                        class="fw-semibold text-dark text-decoration-none">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                </li>
                            @else
                                <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">
                                    {{ $breadcrumb['label'] }}
                                </li>
                            @endif
                        @endforeach

                    </ol>
                </nav>

            </div>
        </div>
    </div>
@endif
