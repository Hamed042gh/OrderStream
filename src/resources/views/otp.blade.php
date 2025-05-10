<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود با کد تأیید</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    ورود با کد تأیید
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    لطفاً شماره موبایل خود را وارد کنید
                </p>
            </div>
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(!session('phone_verified'))
                <form class="mt-8 space-y-6" action="{{ route('login.otp.send') }}" method="POST" id="phoneForm">
                    @csrf
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="phone" class="sr-only">شماره موبایل</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">+98</span>
                                <input id="phone" name="phone" type="text" required 
                                    class="appearance-none rounded-md relative block w-full pl-12 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                    placeholder="9xxxxxxxxx"
                                    pattern="^9[0-9]{9}$"
                                    maxlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <small class="text-gray-500 mt-1 block">شماره موبایل را بدون صفر ابتدایی وارد کنید</small>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            دریافت کد تأیید
                        </button>
                    </div>
                </form>
            @else
                <form class="mt-8 space-y-6" action="{{ route('login.otp.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('phone') }}">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="otp" class="sr-only">کد تأیید</label>
                            <input id="otp" name="otp" type="text" required 
                                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                placeholder="کد 6 رقمی را وارد کنید"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                inputmode="numeric">
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            تأیید کد
                        </button>
                    </div>

                    <div class="text-center">
                        <button type="button" onclick="resendCode()" 
                            class="text-sm text-indigo-600 hover:text-indigo-500">
                            ارسال مجدد کد
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        // Handle form submission
        document.getElementById('phoneForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const phoneInput = document.getElementById('phone');
            const phoneNumber = phoneInput.value;
            const iranMobileRegex = /^9[0-9]{9}$/;
            
            if (!iranMobileRegex.test(phoneNumber)) {
                alert('لطفاً یک شماره موبایل معتبر ایرانی وارد کنید');
                return;
            }
            
            // Add +98 prefix to the phone number
            phoneInput.value = '0' + phoneNumber;
            this.submit();
        });

        // Handle OTP input
        document.getElementById('otp')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function resendCode() {
            const phone = document.querySelector('input[name="phone"]').value;
            fetch('{{ route("login.otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('کد تأیید جدید ارسال شد');
                }
            });
        }
    </script>
</body>
</html>
