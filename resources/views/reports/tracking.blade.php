@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <script>
        function copyCurrentURL() {
            const currentURL = window.location.href;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(currentURL)
                    .then(() => alert('Current URL copied to clipboard!'))
                    .catch(() => alert('Failed to copy URL.'));
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = currentURL;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Current URL copied to clipboard (using fallback)!');
            }
        }
    </script>
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-start mb-3">
                <h1 class="text-center">Laporan ID: {{ $report->id }} </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Laporan: {{ $report->title }}</p>
                </strong>
            </div>
            <div class="col-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Jenis Laporan: {{ $report->type }}</p>
                </strong>
            </div>
            <div class="col-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Deskripsi Laporan: {{ $report->description }}</p>
                </strong>
            </div>
            <div class="col-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Status Laporan: {{ $report->status }}</p>
                </strong>
            </div>
            <div class="col-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Admin Laporan: {{ $report->assignedAdmin->name }}</p>
                </strong>
            </div>
            <div class="col-12 d-flex justify-content-center mb-3">
                <strong>
                    <p class="text-center">Foto Laporan:</p>
                </strong>
            </div>
            <div class="col-12 d-flex justify-content-center mb-3">
                @if ($report->photo)
                    <img src="{{ route('reports.photo.show', $report->id) }}" alt="Foto Laporan"
                        class="img-fluid img-thumbnail vw-25">
                @else
                    <strong>
                        <p>
                            Foto Laporan Tidak Tersedia
                        </p>
                    </strong>
                @endif
            </div>
            <div class="col-12 d-flex justify-content-center mb-3">
                <button class="btn btn-success" onclick="copyCurrentURL()">Copy Link</button>
            </div>
            <div style="height: 150px;"></div>
            <div class="col-12 d-flex justify-content-center mb-3">
                <strong>
                    <p class="text-center text-danger">
                        Jika Anda ingin menutup laporan lebih awal, klik tombol "Close Report"
                    </p>
                </strong>
            </div>
            <div class="col-12 d-flex justify-content-center mb-3">
                <form action="{{ route('reports.update.status', $report->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Closed">
                    <button type="submit" class="btn btn-danger btn-sm">Close Report</button>
                </form>
            </div>
        </div>
    </div>
@endsection
