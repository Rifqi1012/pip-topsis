<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reset Password - Sistem PIP SDN 115 Turangga</title>
</head>
<body style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <div style="background-color: #f8fafc; padding: 40px 20px; text-align: center;">
        <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin: 0 auto; text-align: left;">
            <!-- Header -->
            <tr>
                <td style="background-color: #ffffff; padding: 30px; text-align: center; border-bottom: 2px solid #f1f5f9;">
                    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="SDN 115 Turangga" style="max-height: 80px; width: auto; display: block; margin: 0 auto;">
                    <h2 style="color: #1e293b; font-size: 20px; font-weight: 600; margin: 20px 0 0 0;">Sistem PIP SDN 115 Turangga</h2>
                </td>
            </tr>
            <!-- Body -->
            <tr>
                <td style="padding: 40px 30px; color: #334155; font-size: 16px; line-height: 1.6;">
                    <p style="margin-top: 0;">Halo, <strong>{{ $user->name }}</strong>!</p>
                    <p>Kami menerima permintaan untuk mengatur ulang password akun Anda.</p>
                    <p>Silakan klik tombol di bawah untuk membuat password baru.</p>
                    
                    <div style="text-align: center; margin: 35px 0;">
                        <a href="{{ $url }}" style="background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;">Reset Password</a>
                    </div>
                    
                    <p>Link reset password ini akan kedaluwarsa dalam 60 menit.</p>
                    <p>Jika Anda tidak merasa melakukan permintaan reset password, silakan abaikan email ini. Tidak ada tindakan lebih lanjut yang diperlukan.</p>
                    
                    <p style="margin-bottom: 0;">
                        Terima kasih,<br><br>
                        <strong>Tim IT SDN 115 Turangga</strong>
                    </p>
                </td>
            </tr>
        </table>
        
        <!-- Footer Subtext -->
        <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
            <tr>
                <td style="padding: 20px; text-align: center; font-size: 12px; color: #64748b;">
                    <p style="margin: 0;">&copy; {{ date('Y') }} Sistem PIP SDN 115 Turangga. Semua hak cipta dilindungi.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
