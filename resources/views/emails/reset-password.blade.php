<table>
    <thead>
    <tr>
        <th>hello, mr. {{ $user->name }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>This password reset link will expire in 60 minutes: <a href="{{ route('password.reset',['token'=>$link]) }}">{{ $link }}</a></td>
    </tr>
    </tbody>
</table>
