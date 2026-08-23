<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کد تأیید MiniCRM</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family:Tahoma, Arial, sans-serif; direction:rtl;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                       style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden;">

                    <tr>
                        <td style="padding:30px; text-align:center; background:#111827; color:#ffffff;">

                            <h1 style="margin:0; font-size:28px;">
                                MiniCRM
                            </h1>

                            <p style="margin:10px 0 0; font-size:14px; color:#d1d5db;">
                                تأیید آدرس ایمیل
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px 30px;">

                            <h2 style="margin-top:0; color:#111827;">
                                سلام {{ $verificationCode->user->name }} 👋
                            </h2>

                            <p style="color:#4b5563; line-height:2;">
                                برای تکمیل ثبت‌نام در MiniCRM، کد تأیید زیر را وارد کنید:
                            </p>

                            <div style="margin:30px 0; padding:22px; text-align:center; background:#f3f4f6; border-radius:12px;">

                                <div style="font-size:36px; font-weight:bold; letter-spacing:8px; color:#111827;">
                                    {{ $plainCode }}
                                </div>

                            </div>

                            <p style="color:#6b7280; line-height:2;">
                                این کد تا <strong>۱۰ دقیقه</strong> معتبر است و پس از آن منقضی می‌شود.
                            </p>

                            <p style="color:#6b7280; line-height:2;">
                                اگر شما درخواست ثبت‌نام در MiniCRM را نداده‌اید، این ایمیل را نادیده بگیرید.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; background:#f9fafb; text-align:center;">

                            <p style="margin:0; color:#9ca3af; font-size:12px;">
                                این ایمیل به صورت خودکار توسط MiniCRM ارسال شده است.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>