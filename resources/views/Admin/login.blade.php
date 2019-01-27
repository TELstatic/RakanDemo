<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="/vendor/css/google.css"/>
    <link rel="stylesheet" href="/vendor/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/vendor/css/material-kit.min.css">
    <link rel="stylesheet" href="/vendor/css/material-demo.css">
</head>
<body>
<div class="section section-signup page-header" style="background-image: url('/images/bg3.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2></h2>
                <h4>
                    The most precious thing is life.
                    Life belongs to us only once.
                    People's life should be spent this way:
                    when recalling the past,
                    he is not wasting time and remorse,
                    nor for mediocrity and shame;
                    in the dying,
                    he can say:
                    my whole life and energy,
                    have been dedicated to the world's most magnificent career?
                    Fight for the liberation of mankind.
                </h4>
            </div>
            <div class="col-md-4 ml-auto mr-auto">
                <div class="card card-signup">
                    <form class="form-horizontal" method="POST" action="{{ route('admin.login') }}">
                        {{ csrf_field() }}
                        <div class="card-header card-header-default text-center">
                            <h4>登 录</h4>
                            <div class="social-line">
                                {{--<a href="javascript:;" class="btn btn-link btn-just-icon">--}}
                                {{--<i class="fa fa-weixin"></i>--}}
                                {{--</a>--}}
                                {{--<a href="javascript:;" class="btn btn-link btn-just-icon">--}}
                                {{--<i class="fa fa-qq"></i>--}}
                                {{--</a>--}}
                                <a href="javascript:;" class="btn btn-link btn-just-icon">
                                    {{--<i class="fa fa-weibo"></i>--}}
                                </a>
                            </div>
                        </div>
                        <p class="text-divider"></p>
                        <div class="card-body">
                            <div class="input-group">
                                邮　箱
                                <input type="text" class="form-control" name="email" value="{{ old('email') }}" required
                                       autofocus>
                            </div>

                            @if ($errors->has('email'))
                                <div class="input-group">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </div>
                            @endif

                            <div class="input-group">
                                密　码
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            @if (!$errors->has('email') &&$errors->has('password'))
                                <div class="input-group">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    记住我
                                    <span class="form-check-sign">
                                      <span class="check"></span>
                                  </span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer justify-content-center">
                            <button type="submit" class="btn btn-default">
                                登录
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>