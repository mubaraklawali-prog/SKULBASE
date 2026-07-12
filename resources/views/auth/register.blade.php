<!DOCTYPE html>
<html>
<head>
    <title>Register - Skulbase</title>
</head>
<body>
    <h1>Register Page</h1>

    @if($errors->any())
        <div style="color:red; margin-bottom: 1rem;">
            <strong>Whoops!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Full Name</label><br>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Password</label><br>
        <input type="password" name="password"><br><br>

        <label>Confirm Password</label><br>
        <input type="password" name="password_confirmation"><br><br>

        <button type="submit">Register</button>
    </form>

    <p>
        Already have an account?
        <a href="{{ route('login') }}">Login</a>
    </p>
</body>
</html>
