@extends('layouts.main')
@section('title')
    Login
@endsection

@section('content')
    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                            <img src="{{ asset('assets') }}/img/SMK_KARBAK.png" alt="Logo SMK karbak"
                                                class="img-fluid" style="max-width: 100px;">
                                            <h4 class="d-lg-block mt-2 fw-bold mb-0">Monitoring Siswa PKL</h4>
                                        </div>
                                        <p class="text-center small">Log In</p>
                                    </div>


                                    @error('login_error')
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>Ooops!</strong> {{ $message }}
                                            <button type="button" class="btn-close" data-bsdismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @enderror

                                    <form class="row g-3 needs-validation" novalidate method="POST"
                                        action="{{ route('login') }}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" id="yourUsername"
                                                value="{{ old('username') }}" required>
                                            <div class="invalid-feedback">Please enter your Username.</div>
                                            @error($errors->has('username'))
                                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                            @enderror

                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="yourPassword">
                                            <div class="invalid-feedback">Please enter your password.</div>
                                            @error($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="captcha">
                                                <span>{!! captcha_img() !!}</span>
                                                <button type="button" class="btn btn-success"><i class="bi bi-arrow-repeat"
                                                        id="refresh"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input id="captcha" type="text" class="form-control"
                                                placeholder="Enter Captcha" name="captcha">
                                            @error('captcha')
                                                <span class="text-danger">Captcha yang dimasukan tidak sesuai</span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                        {{-- <div class="col-12">
                                            <p class="small mb-0">Forgot Password? <a
                                                    href="{{ route('forgot-password') }}">Reset here</a></p>
                                        </div> --}}
                                    </form>

                                </div>
                            </div>

                            <div class="credits" style="font-size: x-small;">
                                <!-- All the links in the footer should remain intact. -->
                                <!-- You can delete the links only if you purchased the pro version. -->
                                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>
@endsection
@section('js')
    <script type="text/javascript">
        $('#refresh').click(function() {
            $.ajax({
                type: 'GET',
                url: "{{ route('captcha.refresh') }}",
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endsection
