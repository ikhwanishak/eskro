<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify TAC Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Tambahan untuk menyembunyikan anak panah input nombor */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none;
          margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Verify TAC Code</h2>

        <p class="text-sm text-gray-600 mb-4 text-center">
            Kod TAC telah dihantar ke <strong>{{ $email }}</strong>.
        </p>

        @if(session('error') || isset($error))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
        {{ session('error') ?? $error }}
    </div>
@endif

        <form method="POST" action="{{ route('login.verify') }}" class="space-y-4" id="tac-form">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            
            <input type="hidden" name="code" id="hidden_tac_code">

            <div>
                <label for="code-input-1" class="block text-sm font-medium text-gray-700 mb-1">TAC Code</label>
                
                <div class="flex justify-between gap-2" id="code-inputs">
                    @for ($i = 1; $i <= 6; $i++)
                        <input type="number" id="code-input-{{ $i }}" data-index="{{ $i }}" maxlength="1" required 
                               inputmode="numeric" pattern="\d*" autocomplete="off"
                               class="w-10 h-12 text-center text-xl border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono" />
                    @endfor
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Sahkan & Masuk
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('#code-inputs input');
            const hiddenInput = document.getElementById('hidden_tac_code');
            const form = document.getElementById('tac-form');

            // Tetapkan fokus ke input pertama apabila dimuatkan
            inputs[0].focus();

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    
                    // Padamkan input bukan digit dan hadkan kepada satu karakter
                    e.target.value = value.replace(/[^0-9]/g, '').slice(0, 1);

                    // Lompat ke kotak seterusnya
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    // Handle Backspace: lompat ke input sebelumnya
                    if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            // Handle submission: gabungkan 6 input ke dalam hidden field sebelum hantar ke backend
            form.addEventListener('submit', (e) => {
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                hiddenInput.value = code;

                // Validation asas: pastikan semua 6 kotak diisi
                if (code.length !== 6) {
                    e.preventDefault(); 
                    alert('Sila masukkan 6 digit Kod TAC yang lengkap.');
                    inputs[0].focus();
                }
            });
        });
    </script>

</body>
</html>