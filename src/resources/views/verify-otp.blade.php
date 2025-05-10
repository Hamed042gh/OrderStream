<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأیید کد</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-center mb-6">تأیید شماره موبایل</h2>
            
            <div id="alert" class="hidden mb-4 p-3 rounded"></div>

            <form id="otp-form">
                @csrf
                <input type="hidden" name="phone" value="{{ session('phone') }}">

                <div class="mb-4">
                    <input type="text" 
                        id="otp-input"
                        class="w-full p-3 text-center text-2xl font-bold border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        maxlength="6"
                        placeholder="کد 6 رقمی را وارد کنید"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        required>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <div class="text-center">
                            <div id="timer" class="mb-2">زمان باقیمانده: <span>01:00</span></div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                تأیید کد
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('otp-form');
        const otpInput = document.getElementById('otp-input');
        const alert = document.getElementById('alert');

        function showAlert(message, isError = false) {
            alert.textContent = message;
            alert.classList.remove('hidden', 'bg-red-100', 'bg-green-100');
            alert.classList.add(isError ? 'bg-red-100' : 'bg-green-100');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }

        // فقط اعداد را قبول کن
        otpInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // ارسال فرم
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const response = await fetch('{{ route("login.otp.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        phone: '{{ session("phone") }}',
                        otp: otpInput.value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message);
                    window.location.href = '{{ route("dashboard") }}';
                } else {
                    showAlert(data.error, true);
                }
            } catch (error) {
                showAlert('خطا در ارتباط با سرور', true);
            }
        });

        // تایمر یک دقیقه‌ای
        let timeLeft = 60;
        const timerDisplay = document.querySelector('#timer span');
        const submitBtn = document.querySelector('#submitBtn');

        const timer = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timer);
                submitBtn.disabled = true;
                timerDisplay.textContent = 'زمان به پایان رسید';
            }
        }, 1000);

        // اعتبارسنجی فرم
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            const otp = otpInput.value;
            if (otp.length !== 6) {
                e.preventDefault();
                alert('لطفاً کد 6 رقمی را وارد کنید');
                return;
            }
        });
    </script>
</body>
</html> 