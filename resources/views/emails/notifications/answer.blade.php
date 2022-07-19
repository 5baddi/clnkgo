@extends('layouts.mail')

@section('title')
{{ $subject }}
@endsection

@section('content')
<tr>
    <td bgcolor="#ffffff" align="left"
        style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
        <p style="margin: 0;">Hi! {{ $tweet->author->name ? ucwords($tweet->author->name) : '@' . $tweet->author->username }} 👋</p>
        <p style="margin: 0;">You have new answer: </p>
        <p style="margin: 0;padding: 20px 0px;">{{ $answer->content }}</p>
    </td>
</tr>
@endsection