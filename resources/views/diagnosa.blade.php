@extends('layouts.app')

@section('title', 'Diagnosa Penyakit')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <style>
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 20px;
        }
        .step-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #dee2e6;
            transition: background .3s, transform .3s;
        }
        .step-dot.active   { background: #1a73e8; transform: scale(1.4); }
        .step-dot.done     { background: #28a745; }

        .pertanyaan-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 10px;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }
        .pertanyaan-item:hover { border-color: #1a73e8; box-shadow: 0 2px 8px rgba(26,115,232,.1); }
        .pertanyaan-item.answered { border-color: #28a745; background: #f6fff8; }

        .cf-select {
            min-width: 160px;
            border-radius: 6px;
            font-size: .85rem;
            cursor: pointer;
        }
        /* Warna opsi berdasarkan keyakinan */
        .cf-select option[value$="+0"]   { color: #dc3545; }
        .cf-select option[value$="+0.2"] { color: #fd7e14; }
        .cf-select option[value$="+0.4"] { color: #ffc107; }
        .cf-select option[value$="+0.6"] { color: #20c997; }
        .cf-select option[value$="+0.8"] { color: #0d6efd; }
        .cf-select option[value$="+1"]   { color: #198754; }

        .progress-cf {
            height: 6px;
            border-radius: 3px;
            background: #e9ecef;
            margin-top: 16px;
            overflow: hidden;
        }
        .progress-cf-bar {
            height: 100%;
            background: linear-gradient(90deg, #1a73e8, #28a745);
            border-radius: 3px;
            transition: width .4s ease;
        }

        .page-counter {
            font-size: .82rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 8px;
        }

        .info-box {
            background: linear-gradient(135deg, #e8f0fe, #f0f7ff);
            border-left: 4px solid #1a73e8;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-size: .9rem;
            color: #444;
            line-height: 1.7;
        }
        .info-box .info-title {
            font-weight: 700;
            color: #1a73e8;
            margin-bottom: 4px;
            font-size: 1rem;
        }

        /* Badge keyakinan */
        .badge-keyakinan {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: .75rem;
            font-weight: 600;
            margin-left: 8px;
        }
    </style>
@endpush

@section('main')
<div class="content-wrapper">

    {{-- Info Box --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="info-box">
                <div class="info-title"><i class="mdi mdi-stethoscope mr-1"></i>Petunjuk Diagnosa</div>
                Pilih tingkat keyakinan Anda terhadap setiap gejala yang ditampilkan.
                Jawaban yang lebih akurat akan menghasilkan diagnosa yang lebih tepat.
                Gunakan skala dari <strong>Sangat Tidak Yakin</strong> hingga <strong>Sangat Yakin</strong>.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-4">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0 font-weight-bold">
                            <i class="mdi mdi-clipboard-pulse-outline text-primary mr-2"></i>Form Diagnosa
                        </h4>
                        <span class="badge badge-primary px-3 py-2" id="progressBadge" style="font-size:.82rem; border-radius:20px;">
                            0 / {{ count($pertanyaan) }} dijawab
                        </span>
                    </div>

                    {{-- Progress bar total --}}
                    <div class="progress-cf mb-3">
                        <div class="progress-cf-bar" id="progressBar" style="width:0%"></div>
                    </div>

                    {{-- Step dots --}}
                    @php $totalPages = ceil(count($pertanyaan) / 5); @endphp
                    <div class="step-indicator" id="stepIndicator">
                        @for($i = 0; $i < $totalPages; $i++)
                            <div class="step-dot {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i }}"></div>
                        @endfor
                    </div>

                    {{-- Page counter --}}
                    <div class="page-counter" id="pageCounter">
                        Halaman <span id="currentPageNum">1</span> dari {{ $totalPages }}
                    </div>

                    <form action="{{ route('diagnosa.simpanDiagnosa') }}" method="post" id="diagnosaForm">
                        @csrf
                        <div id="pertanyaanContainer">
                            @foreach($pertanyaan as $key => $value)
                                @php $page = floor($key / 5); @endphp
                                <div class="pertanyaan-group" data-page="{{ $page }}"
                                     style="{{ $page > 0 ? 'display:none;' : '' }}">
                                    <div class="pertanyaan-item d-flex align-items-center justify-content-between flex-wrap gap-2"
                                         id="item-{{ $key }}">
                                        <div class="d-flex align-items-start" style="flex:1; min-width:0;">
                                            <span class="badge badge-light text-muted mr-2 mt-1"
                                                  style="min-width:28px; text-align:center;">{{ $loop->iteration }}</span>
                                            <span style="font-size:.92rem; line-height:1.5;">{{ $value->pertanyaan }}</span>
                                        </div>
                                        <div class="mt-2 mt-md-0">
                                            <select name="diagnosa[]"
                                                    class="form-control cf-select diagnosa-option"
                                                    data-index="{{ $key }}"
                                                    data-page="{{ $page }}">
                                                <option value="" selected>— Pilih —</option>
                                                <option value="{{ $value->idgejala }}+0">😶 Sangat Tidak Yakin</option>
                                                <option value="{{ $value->idgejala }}+0.2">😕 Tidak Yakin</option>
                                                <option value="{{ $value->idgejala }}+0.4">😐 Kurang Yakin</option>
                                                <option value="{{ $value->idgejala }}+0.6">🙂 Cukup Yakin</option>
                                                <option value="{{ $value->idgejala }}+0.8">😊 Yakin</option>
                                                <option value="{{ $value->idgejala }}+1">😄 Sangat Yakin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigasi --}}
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary px-4" id="prevPage" style="display:none;">
                                <i class="mdi mdi-chevron-left mr-1"></i> Sebelumnya
                            </button>
                            <div></div>
                            <button type="button" class="btn btn-primary px-4" id="nextPage">
                                Selanjutnya <i class="mdi mdi-chevron-right ml-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success px-4" id="submitDiagnosa"
                                    style="display:none;" disabled>
                                <i class="mdi mdi-check-circle mr-1"></i> Diagnosa Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects       = document.querySelectorAll('.diagnosa-option');
    const submitBtn     = document.getElementById('submitDiagnosa');
    const nextBtn       = document.getElementById('nextPage');
    const prevBtn       = document.getElementById('prevPage');
    const groups        = document.querySelectorAll('.pertanyaan-group');
    const dots          = document.querySelectorAll('.step-dot');
    const progressBar   = document.getElementById('progressBar');
    const progressBadge = document.getElementById('progressBadge');
    const pageCounter   = document.getElementById('currentPageNum');
    const totalQ        = selects.length;
    const totalPages    = dots.length;

    let currentPage = 0;

    function showPage(page) {
        groups.forEach(g => g.style.display = g.getAttribute('data-page') == page ? '' : 'none');
        prevBtn.style.display  = page > 0 ? '' : 'none';
        nextBtn.style.display  = page < totalPages - 1 ? '' : 'none';
        submitBtn.style.display = page === totalPages - 1 ? '' : 'none';
        pageCounter.textContent = page + 1;

        // Update dots
        dots.forEach((d, i) => {
            d.classList.remove('active', 'done');
            if (i < page)  d.classList.add('done');
            if (i === page) d.classList.add('active');
        });
    }

    function updateProgress() {
        let answered = 0;
        selects.forEach(s => { if (s.value !== '') answered++; });
        const pct = Math.round((answered / totalQ) * 100);
        progressBar.style.width = pct + '%';
        progressBadge.textContent = answered + ' / ' + totalQ + ' dijawab';
        submitBtn.disabled = answered < totalQ;
    }

    function markAnswered(index, answered) {
        const item = document.getElementById('item-' + index);
        if (item) {
            if (answered) item.classList.add('answered');
            else          item.classList.remove('answered');
        }
    }

    selects.forEach(s => {
        s.addEventListener('change', function () {
            markAnswered(this.getAttribute('data-index'), this.value !== '');
            updateProgress();
        });
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages - 1) { currentPage++; showPage(currentPage); }
    });
    prevBtn.addEventListener('click', () => {
        if (currentPage > 0) { currentPage--; showPage(currentPage); }
    });

    // Klik dot untuk navigasi langsung
    dots.forEach((d, i) => {
        d.style.cursor = 'pointer';
        d.addEventListener('click', () => { currentPage = i; showPage(i); });
    });

    showPage(0);
    updateProgress();
});
</script>
@endpush
