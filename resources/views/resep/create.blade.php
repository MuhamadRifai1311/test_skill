<x-app-layout>
    <div class="mx-auto p-4">
        <h1 class="text-2xl font-bold">Tambah Resep</h1>
        <p class="text-base">Non Racikan</p>
        <div class="mt-4">
            <form action="{{ route('resep.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="hidden" name="resep_tipe" value="Non-Racikan">
                    <input type="hidden" name="resep_tanggal" value="{{ now() }}">
                    <!-- obat -->
                    <div class="relative w-full mb-4">
                        <label for="nama_obat" class="block mb-1">Pilih Obat :</label>
                        <input type="text" id="search-obat" name="obat" class="input w-full"
                            placeholder="Ketik nama obat..." required>
                        <div class="absolute z-10 w-full">
                            <select id="obat-select" size="5"
                                class="hidden w-full mt-1 border border-gray-300 bg-white shadow-md rounded overflow-x-scroll">
                            </select>
                        </div>
                    </div>

                    <!-- Signa -->
                    <div class="relative w-full mb-4">
                        <label for="signa" class="block mb-1">Signa :</label>
                        <input type="text" id="search-signa" name="signa" class="input w-full"
                            placeholder="Ketik signa..." required>
                        <div class="absolute z-10 w-full">
                            <select id="signa-select" size="5"
                                class="hidden w-full mt-1 border border-gray-300 bg-white shadow-md rounded">
                            </select>
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div class="w-full mb-4">
                        <label for="jumlah" class="block mb-1">Jumlah :</label>
                        <input type="number" id="jumlah" name="jumlah" class="input w-full"
                            placeholder="Masukkan jumlah obat" required>
                    </div>
                </div>


                <div>
                    <h1 class="text-2xl font-bold">Resep draft</h1>
                </div>
                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">

                    <table class="table">

                        <thead class="text-black">
                            <tr>
                                <th>Nama Obat</th>
                                <th>Signa</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Data akan ditambahkan di sini -->
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between mt-4 items-center">
                    <a href="{{ route('resep.index') }}" class="btn btn-warning">Kembali</a>
                    <button type="submit" name="submit" class="btn btn-primary ">Simpan Resep</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const input = document.getElementById('search-obat');
        const select = document.getElementById('obat-select');
        const signaInput = document.getElementById('search-signa');
        const signaSelect = document.getElementById('signa-select');
        const jumlahInput = document.getElementById('jumlah');
        const tableBody = document.getElementById('table-body');

        let isSubmitted = false;

        input.addEventListener('input', function() {
            if (isSubmitted) return;
            const search = this.value;

            fetch(`/resep/get-obat?search=${encodeURIComponent(search)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(data => {
                    select.innerHTML = '';

                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.textContent = 'Tidak ada hasil';
                        option.value = '';
                        option.disabled = true;
                        select.appendChild(option);
                        select.style.display = 'block';
                        return;
                    }

                    if (search.trim() === '') {
                        select.style.display = 'none';
                        return;
                    }

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.textContent = `${item.obatalkes_nama} (${Number(item.stok)} stok)`;
                        option.value = item.obatalkes_id;

                        if (item.stok <= 0) {
                            option.disabled = true;
                            option.textContent += ' - Habis';
                        }

                        select.appendChild(option);
                    });

                    select.style.display = 'block';
                })
                .catch(error => {
                    console.error('Gagal mengambil data:', error);
                    select.style.display = 'none';
                });
        });

        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.disabled) {
                const obatNama = selectedOption.textContent.split(' (')[0];
                input.value = obatNama;
                select.style.display = 'none';
            }
        });

        signaInput.addEventListener('input', function() {
            if (isSubmitted) return;
            const search = this.value;

            fetch(`/resep/get-signa?search=${encodeURIComponent(search)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(data => {
                    signaSelect.innerHTML = '';

                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.textContent = 'Tidak ada hasil';
                        option.value = '';
                        option.disabled = true;
                        signaSelect.appendChild(option);
                        signaSelect.style.display = 'block';
                        return;
                    }

                    if (search.trim() === '') {
                        signaSelect.style.display = 'none';
                        return;
                    }

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.textContent = item.signa_nama;
                        option.value = item.signa_id;
                        signaSelect.appendChild(option);
                    });

                    signaSelect.style.display = 'block';
                })
                .catch(error => {
                    console.error('Gagal mengambil data:', error);
                    signaSelect.style.display = 'none';
                });
        });

        signaSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.disabled) {
                signaInput.value = selectedOption.textContent;
                signaSelect.style.display = 'none';
            }
        });


        function isInputValid() {
            const obatId = select.value;
            const signaId = signaSelect.value;
            const jumlah = jumlahInput.value;
            const stokMatch = select.options[select.selectedIndex]?.textContent.match(/\((\d+) stok\)/);

            if (!obatId || !signaId || !jumlah) return false;

            if (stokMatch && parseInt(jumlah) > parseInt(stokMatch[1])) {
                alert('Jumlah melebihi stok yang tersedia!');
                jumlahInput.value = '';
                return false;
            }

            return true;
        }

        function insertRow() {
            const obatId = select.value;
            const obatNama = input.value;
            const signaId = signaSelect.value;
            const signaNama = signaInput.value;
            const jumlah = jumlahInput.value;

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${obatNama}</td>
            <td>${signaNama}</td>
            <td>${jumlah}</td>
        `;
            tableBody.appendChild(row);

            const form = document.querySelector('form');
            const index = document.querySelectorAll('input[name^="resep_details"]').length / 3;

            form.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="resep_details[${index}][obatalkes_id]" value="${obatId}">
            <input type="hidden" name="resep_details[${index}][signa_id]" value="${signaId}">
            <input type="hidden" name="resep_details[${index}][jumlah]" value="${jumlah}">
        `);


            input.disabled = true;
            select.disabled = true;
            signaInput.disabled = true;
            signaSelect.disabled = true;
            jumlahInput.disabled = true;
            isSubmitted = true;
        }


        jumlahInput.addEventListener('blur', function() {
            if (!isSubmitted && isInputValid()) {
                insertRow();
            }
        });
    </script>

</x-app-layout>
