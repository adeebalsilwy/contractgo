<!-- 
    مثال عملي: تطبيق RTL على صفحة تسجيل الدخول
    Practical Example: Applying RTL to Login Page
-->

@extends('layout')
<title>{{ get_label('login', 'Login') }} - {{ $general_settings['company_title'] }}</title>

@section('content')
@php
    // متغيرات RTL
    $isRtl = is_rtl();
    $direction = get_text_direction();
    $align = get_text_align();
@endphp

<div class="container-fluid" dir="{{ $direction }}">
    @if (config('constants.ALLOW_MODIFICATION') === 0)
        <div class="col-12 mt-4 text-center">
            <div class="alert alert-warning mb-0">
                <b>{{ get_label('note', 'Note') }}:</b> 
                {{ get_label('modification_notice', 'If you cannot log in here...') }}
            </div>
        </div>
    @endif
    
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card">
                <div class="card-body" style="text-align: {{ $align }}">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset($general_settings['full_logo']) }}" 
                                     width="300px" 
                                     alt="" />
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    
                    <h4 class="mb-2">
                        {{ get_label('welcome_to', 'Welcome to') }} 
                        <?= $general_settings['company_title'] ?>! 👋
                    </h4>
                    
                    <p class="mb-4">
                        {{ get_label('sign_into_your_account', 'Sign into your account') }}
                    </p>
                    
                    <form id="formAuthentication" 
                          class="form-submit-event mb-3"
                          action="{{ url('users/authenticate') }}" 
                          method="POST"
                          dir="{{ $direction }}">
                        <input type="hidden" name="redirect_url" value="{{ url('home') }}">
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" 
                                   class="form-label" 
                                   style="text-align: {{ $align }}">
                                {{ get_label('email', 'Email') }} 
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="email" 
                                   name="email"
                                   placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>"
                                   value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? 'admin@gmail.com' : '' ?>"
                                   autofocus 
                                   style="text-align: {{ $align }}" />
                        </div>
                        
                        <!-- Password Field -->
                        <div class="form-password-toggle mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" 
                                       for="password"
                                       style="text-align: {{ $align }}">
                                    {{ get_label('password', 'Password') }} 
                                    <span class="asterisk">*</span>
                                </label>
                                <a href="{{ url('forgot-password') }}">
                                    <small>{{ get_label('forgot_password', 'Forgot Password') }}?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" 
                                       id="password" 
                                       class="form-control" 
                                       name="password"
                                       placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>"
                                       value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? '123456' : '' ?>"
                                       aria-describedby="password"
                                       style="text-align: {{ $align }}" />
                                <span class="input-group-text cursor-pointer">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Captcha (if enabled) -->
                        @php
                            $settings = get_settings('general_settings');
                        @endphp
                        
                        @if(!empty($settings['recaptcha_enabled']) && $settings['recaptcha_enabled'])
                            <div class="mb-4">
                                <label class="form-label d-block" style="text-align: {{ $align }}">
                                    {{ get_label('captcha', 'Captcha') }} 
                                    <span class="asterisk">*</span>
                                </label>
                                <div class="d-flex justify-content-{{ $align === 'right' ? 'start' : 'end' }}">
                                    {!! NoCaptcha::display() !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="text-danger small d-block mt-1" style="text-align: {{ $align }}">
                                        {{ $errors->first('g-recaptcha-response') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Submit Button -->
                        <div class="mb-4">
                            <button class="btn btn-primary d-grid w-100" 
                                    id="submit_btn"
                                    type="submit"
                                    style="text-align: center">
                                {{ get_label('login', 'Login') }}
                            </button>
                        </div>
                        
                        <!-- Signup Link -->
                        @if (!isset($general_settings['allowSignup']) || $general_settings['allowSignup'] == 1)
                            <div class="text-center">
                                <p class="mb-{{ config('constants.ALLOW_MODIFICATION') === 0 ? '3' : '0' }}">
                                    {{ get_label('dont_have_account', 'Don\'t have an account?') }} 
                                    <a href="{{ url('signup') }}">
                                        {{ get_label('sign_up', 'Sign Up') }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        <!-- Demo Mode Buttons -->
                        @if (config('constants.ALLOW_MODIFICATION') === 0)
                            <div class="mb-3">
                                <button class="btn btn-success d-grid w-100 admin-login" type="button">
                                    {{ get_label('login_as_admin', 'Login As Admin') }}
                                </button>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-info d-grid w-100 member-login" type="button">
                                    {{ get_label('login_as_member', 'Login As Team Member') }}
                                </button>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-warning d-grid w-100 client-login" type="button">
                                    {{ get_label('login_as_client', 'Login As Client') }}
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{!! NoCaptcha::renderJs() !!}
@endsection

{{-- 
    ملاحظات RTL:
    RTL Notes:
    
    1. أضفنا dir="{{ $direction }}" للحاوية الرئيسية
    2. استخدمنا style="text-align: {{ $align }}" للنصوص والحقول
    3. حافظنا على التوسيط للأزرار باستخدام text-align: center
    4. استخدمنا دوال المساعدة بدلاً من القيم الثابتة
    
    Key Changes:
    1. Added dir="{{ $direction }}" to main container
    2. Used style="text-align: {{ $align }}" for text and fields
    3. Kept buttons centered with text-align: center
    4. Used helper functions instead of hardcoded values
--}}
