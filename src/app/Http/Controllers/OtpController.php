<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class OtpController extends Controller
{
    private const OTP_LENGTH = 6;
    private const OTP_EXPIRY_MINUTES = 1;
    private const SENDER_NUMBER = "2000660110";


    public function sendOtp(Request $request)
    {
        try {
            $this->validatePhoneNumber($request);
            $phoneNumber = $request->phone;
            
            $otp = $this->generateOtp();
            $this->saveOtp($phoneNumber, $otp);
            $this->sendOtpSms($phoneNumber, $otp);

            return $this->successfulOtpResponse($phoneNumber);

        } catch (ApiException $e) {
            Log::error('Kavenegar API Error: ' . $e->errorMessage());
            return $this->errorResponse('مشکلی در ارسال پیامک رخ داده است.');
        } catch (HttpException $e) {
            Log::error('Kavenegar HTTP Error: ' . $e->errorMessage());
            return $this->errorResponse('خطا در اتصال به سرویس پیامک.');
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return $this->errorResponse('خطای پیش‌بینی نشده.');
        }
    }


    public function showOtpForm()
    {
        return view('verify-otp');
    }

  
    public function verifyOtp(Request $request)
    {
        $this->validateOtpRequest($request);
        
        $otp = $this->findValidOtp($request->phone, $request->otp);
        
        if (!$otp) {
            return $this->errorResponse('کد تأیید نامعتبر یا منقضی شده است.', 400);
        }

        $this->markOtpAsUsed($otp);
        
        return $this->successfulVerificationResponse();
    }

    private function validatePhoneNumber(Request $request): void
    {
        $request->validate([
            'phone' => 'required|string|min:10'
        ]);
    }


    private function generateOtp(): string
    {
        return str_pad(
            random_int(0, 999999),
            self::OTP_LENGTH,
            '0',
            STR_PAD_LEFT
        );
    }

    private function saveOtp(string $phoneNumber, string $otp): void
    {
        Otp::create([
            'phone' => $phoneNumber,
            'code' => $otp,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES)
        ]);
    }


    private function sendOtpSms(string $phoneNumber, string $otp): void
    {
        $result = Kavenegar::Send(
            self::SENDER_NUMBER,
            [$phoneNumber],
            " {$otp}"
        );

        if ($result) {
            foreach ($result as $r) {
                Log::info("OTP Sent", (array) $r);
            }
        }
    }


    private function successfulOtpResponse(string $phoneNumber)
    {
        return redirect()->route('verify.otp')->with([
            'success' => true,
            'message' => 'کد تأیید ارسال شد.',
            'phone' => $phoneNumber
        ]);
    }


    private function validateOtpRequest(Request $request): void
    {
        $request->validate([
            'phone' => 'required|string|min:10',
            'otp' => 'required|digits:6'
        ]);
    }


    private function findValidOtp(string $phoneNumber, string $otp): ?Otp
    {
        return Otp::where('phone', $phoneNumber)
            ->where('code', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    private function markOtpAsUsed(Otp $otp): void
    {
        $otp->update(['is_used' => true]);
    }


    private function successfulVerificationResponse()
    {
        return response()->json([
            'success' => true,
            'message' => 'ورود موفقیت‌آمیز بود.'
        ]);
    }


    private function errorResponse(string $message, int $status = 500)
    {
        return response()->json(['error' => $message], $status);
    }
}