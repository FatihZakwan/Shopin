<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Selamat Datang di SHOPIN</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 20px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <tr>
            <td align="center" style="background-color: #4f46e5; padding: 30px 20px;">
                <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">SHOPIN</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="color: #1f2937; margin-top: 0; font-size: 20px;">Halo, {{ $name }}! 👋</h2>
                <p style="color: #4b5563; font-size: 15px; line-height: 1.6;">
                    Selamat datang di <strong>SHOPIN</strong>! Akun Anda dengan email <strong>{{ $email }}</strong> telah berhasil terdaftar.
                </p>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ url('/') }}" style="background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: bold; display: inline-block;">Mulai Belanja</a>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color: #f9fafb; padding: 20px; border-top: 1px solid #f3f4f6; color: #9ca3af; font-size: 12px;">
                <p style="margin: 0;">&copy; {{ date('Y') }} SHOPIN. Project Pembelajaran Laravel.</p>
            </td>
        </tr>
    </table>
</body>
</html>