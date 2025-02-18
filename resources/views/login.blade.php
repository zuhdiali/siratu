<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link
    rel="icon"
    href="{{asset('img/logo.svg')}}"
    type="image/x-icon"
    />
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4>Login ke Website SIRAGI</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('login.post')}}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="my-2" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label class="my-2" for="password">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="togglePassword">
                                    <label class="form-check-label" for="togglePassword">
                                        Lihat kata sandi
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        {{-- <a href="{{ route('index') }}" class="btn btn-secondary mt-3">Masuk tanpa login</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('js/core/popper.min.js')}}"></script>
    <script src="{{asset('js/core/bootstrap.min.js')}}"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('change', function (e) {
            const password = document.getElementById('password');
            const type = this.checked ? 'text' : 'password';
            password.setAttribute('type', type);
        });
    </script>
</body>
</html>
