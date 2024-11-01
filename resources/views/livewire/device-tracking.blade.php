<div class="flex flex-col gap-4">
    <h1 class="text-2xl font-bold">Perangkat Terdeteksi {{ count($device_positions) }}</h1>
    <div class="flex flex-col gap-2">
        @if($plot_url)
            <img class="rounded-xl" src="{{ env("FLASK_API", "http://localhost:5000").$plot_url }}" alt="Plot">
        @else
        @endif
        <div class="text-md flex flex-col gap-1">
            Plot Peta Tidak Tersedia
            <div class="text-xs">Terdapat kesalahan pada server</div>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-medium">Posisi Perangkat</h2>
        <ul>
            @foreach($device_positions as $device)
                <li>{{ $device['name'] }}: ({{ $device['x'] }}, {{ $device['y'] }})</li>
            @endforeach
        </ul>
    </div>
</div>
