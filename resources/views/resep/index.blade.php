<x-app-layout>
    <div class="mx-auto p-4">
        <h1 class="text-2xl mb-4 font-bold">Resep List</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-row justify-end items-center gap-2 mb-6 mt-4">
            <div>
                <p class="text-lg">tambah resep :</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('resep.create') }}">
                    <button class="btn btn-primary text-md">Non Racikan</button>
                </a>
                <a href="{{ route('resep.racikan.create') }}">
                    <button class="btn btn-primary text-md">Racikan</button>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe Resep</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reseps as $resep)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $resep->resep_tipe }}</td>
                            <td>{{ \Carbon\Carbon::parse($resep->resep_tanggal)->format('d-M-y') }}</td>
                            <td>
                                <a href="{{ route('resep.show', $resep->id) }}">
                                    <button class="btn btn-info">Detail</button>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500">Belum ada data resep.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
