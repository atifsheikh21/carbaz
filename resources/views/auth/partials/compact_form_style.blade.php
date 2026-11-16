{{-- Compact, centred card for the Sign In / Sign Up pages. --}}
<style>
    section.login{
        padding: 48px 0 64px;
    }
    section.login .login-bg{
        max-width: {{ $maxWidth ?? '480px' }};
        margin-left: auto;
        margin-right: auto;
        padding: 36px 36px 32px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(17, 24, 39, .08);
    }
    section.login .login-head{
        margin-bottom: 8px;
    }
    section.login .login-head h3{
        font-size: 26px;
        margin-bottom: 4px;
    }
    section.login .form-label{
        font-size: 14px;
        margin-bottom: 6px;
    }
    section.login .form-control,
    section.login .form-select{
        height: 46px;
        border-radius: 10px;
        font-size: 15px;
    }
    section.login textarea.form-control{
        height: auto;
    }
    section.login .login-form-item{
        margin-top: 16px !important;
    }
    section.login .thm-btn-two{
        height: 48px;
        border-radius: 10px;
    }
    @media (max-width: 575.98px){
        section.login{
            padding: 24px 0 40px;
        }
        section.login .login-bg{
            padding: 24px 18px 22px;
            border-radius: 14px;
        }
        section.login .login-head h3{
            font-size: 22px;
        }
    }
</style>
