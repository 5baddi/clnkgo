@extends('layouts.mail')

@section('title')
{{ $subject }}
@endsection

@section('content')
<tr>
    <td bgcolor="#ffffff" align="left"
        style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
        <p style="margin: 0;">Hi! {{ ucwords($user->first_name) }} 👋</p>
        <p style="margin: 0;">We've received a request to reset your account password associated with {{ $user->email }}, No changes have been made to your account yet.</p>
        <p style="margin: 0;padding: 20px 0px;">You can reset your password by clicking the link below:</p>
    </td>
</tr>
<tr>
    <td bgcolor="#ffffff" align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" style="border-radius: 3px;" bgcolor="#04AF90"><a
                                    href="{{ route('password', ['token' => $token]) }}" target="_blank"
                                    style="font-size: 1.2rem; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: .7rem 1.5rem; border-radius: 4px; border: 1px solid #04AF90; display: inline-block;">Reset your password</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td bgcolor="#ffffff" align="left"
        style="padding: 0px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
        <p style="margin: 0;">If that doesn't work, copy and paste the following link in your
            browser:</p>
    </td>
</tr>
<tr>
    <td bgcolor="#ffffff" align="left"
        style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
        <p style="margin: 0;"><a href="{{ route('password', ['token' => $token]) }}" target="_blank"
                style="color: #04AF90;">{{ route('password', ['token' => $token]) }}</a></p>
    </td>
</tr>
@endsection