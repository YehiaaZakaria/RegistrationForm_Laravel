@extends('master')

@section('title', trans('messages.registration_form'))

@section('content')
<center>
    <div class="title-container">
        <h2 class="title">{{ trans('messages.registration_form') }}</h2>
        <div class="language-switch">
            <a href="{{ route('setLocale', app()->getLocale() == 'en' ? 'ar' : 'en') }}" class="language-toggle">{{ app()->getLocale() == 'en' ? 'عربي' : 'English' }}</a>
        </div>
    </div>
</center>



<form method="POST" id="form" action="/save_user" enctype="multipart/form-data">
    @csrf
    <div>
        <div id="nameValidation" style="color:red;"></div>

        <center>
            <div>
                <input type="text" required name="full_name" id="full_name" class="fullName" placeholder="{{ trans('messages.full_name') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>
        </center>

        <div id="usernameValidation" style="color:red;"></div>

        <center>
            <div>
                <input type="text" required name="user_name" class="userName" placeholder="{{ trans('messages.user_name') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>

            <div>
                <input type="text" placeholder="{{ trans('messages.birthdate') }}" onfocus="(this.type='date')" required id="birthdate"
                    name="birthdate" class="dob" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <button type="button" id="getBornTodayButton">{{ trans('messages.getBornToday') }}</button>
            </div>

            <div>
                <input type="Email" required name="email" class="email" placeholder="{{ trans('messages.email') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>
            <div id="emailValidation" style="color:red; text-align:left;"></div>

            <div>
                <input type="text" required name="phone" class="phone" placeholder="{{ trans('messages.phone') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>

            <div>
                <input type="text" required name="address" class="address" placeholder="{{ trans('messages.address') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>
        </center>

        <div id="passwordValidation" style="color:red;"></div>

        <center>
            <div>
                <input type="password" required id="password" name="password" class="password" placeholder="{{ trans('messages.password') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>
        </center>

        <div id="passwordMatch" style="color:red;"></div>

        <center>
            <div>
                <input type="password" required id="confirmPassword" name="confirm_password" class="passwordConfirm"
                    placeholder="{{ trans('messages.confirm_password') }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>

            <div class="fileContainer">
                <input type="file" name="user_image" id="user_image" accept="image/*" required dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            </div>

            <button type="submit" id="submitButton" class="submit">{{ trans('messages.submit') }}</button>
        </center>

        <div id="nameTableContainer"></div>
    </div>
</form>
<script>
    $('#form').submit(function (event) {
    var form = $(this);

    event.preventDefault();

    var formData = new FormData(form[0]);

    // Clear error messages for username and email
    $('#usernameValidation').html("");
    $('#emailValidation').html("");

    if (checkPasswordValidation() && checkPasswordMatch() && checkNameValidation()) {
        $.ajax({
            url: 'save_user',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.success);
                    form[0].reset();
                    $('#usernameValidation').html("");
                } else if (response.error === "Username already exists.") {
                    $('#usernameValidation').html("*{{ trans('messages.username_exists_message') }}");
                } else if (response.error === "Email already exists.") {
                    $('#emailValidation').html("*{{ trans('messages.email_exists_message') }}");
                } else {
                    alert(response.error);
                }
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
        $('#nameTableContainer').html("");
    }
});


    $('#full_name').keyup(function () {
        if (checkNameValidation()) {
            $('#nameValidation').html("");
        }
    });

    $('#password').keyup(function () {
        if (checkPasswordValidation()) {
            $('#passwordValidation').html("");
        }
    });

    $('#confirmPassword').keyup(function () {
        if (checkPasswordMatch()) {
            $('#passwordMatch').html("");
        }
    });

    function checkPasswordMatch() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {
            $("#passwordMatch").html("*{{ trans('messages.password_match_message') }}");
            return false;
        }
        return true;
    }

    function checkPasswordValidation() {
        var password = document.getElementById("password").value;
        var pattern = /^(?=.*[!@#$%^&*-])(?=.*[0-9]).{8,}$/;

        if (!pattern.test(password)) {
            $("#passwordValidation").html("*{{ trans('messages.password_validation_message') }}");
            return false;
        }

        return true;
    }

    function checkNameValidation() {
        const inputField = document.getElementById("full_name");
        const inputValue = inputField.value;
        var pattern = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

        if (pattern.test(inputValue)) {
            $("#nameValidation").html("{{ trans('messages.name_validation_message') }}");
            return false;
        }
        return true;
    }

    $('#getBornTodayButton').click(function () {
        var birthdate = $('#birthdate').val();

        if (!birthdate) {
            alert("{{ trans('messages.birthdate_required_message') }}");
            return;
        }

        alert("{{ trans('messages.wait_message') }}");

        var birthdateParts = birthdate.split('-');
        var month = birthdateParts[1];
        var day = birthdateParts[2];

        $.ajax({
            url: 'getBornToday',
            type: 'get',
            data: {
                day: day,
                month: month
            },
            success: function (response) {
                $('#nameTableContainer').html(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    var errorMessage = "{{ trans('messages.fill_field_message') }}";

</script>

@endsection