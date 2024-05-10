@extends('master')

@section('title', 'Registration Form')

@section('content')
    <center>
        <h2 class="title">Registration Form</h2>
    </center>

    <form method="POST" id="form" action="/save_user" enctype="multipart/form-data">
        @csrf
        <div>
            <div id="nameValidation" style="color:red;"></div>

            <center>
                <div>
                    <input type="text" required name="full_name" id="full_name" class="fullName" placeholder="Full Name">
                </div>
            </center>

            <div id="usernameValidation" style="color:red;"></div>

            <center>
                <div>
                    <input type="text" required name="user_name" class="userName" placeholder="User Name">
                </div>

                <div>
                    <input type="text" placeholder="Birthdate" onfocus="(this.type='date')" required id="birthdate"
                        name="birthdate" class="dob">
                    <button type="button" id="getBornTodayButton">Get names born on the same day</button>
                </div>

                <div>
                    <input type="Email" required name="email" class="email" placeholder="Email">
                </div>
                <div id="emailValidation" style="color:red; text-align:left;"></div>

                <div>
                    <input type="text" required name="phone" class="phone" placeholder="Phone Number">
                </div>

                <div>
                    <input type="text" required name="address" class="address" placeholder="Address">
                </div>
            </center>

            <div id="passwordValidation" style="color:red;"></div>

            <center>
                <div>
                    <input type="password" required id="password" name="password" class="password" placeholder="Password">
                </div>
            </center>

            <div id="passwordMatch" style="color:red;"></div>

            <center>
                <div>
                    <input type="password" required id="confirmPassword" name="confirm_password" class="passwordConfirm"
                        placeholder="Confirm Password">
                </div>

                <div class="fileContainer">
                    <input type="file" name="user_image" id="user_image" accept="image/*" required>
                </div>

                <button type="submit" id="submitButton" class="submit">Submit</button>
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
                    if (response === "User registered successfully.") {
                        alert(response);
                        form[0].reset();
                        $('#usernameValidation').html("");
                    } else if (response === "Username already exists.") {
                        $('#usernameValidation').html("*Username already exists.");
                    } else if (response === "Email already exists.") {
                        $('#emailValidation').html("*Email already exists.");
                    } else {
                        alert(response);
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
            $("#passwordMatch").html("*Passwords do not match.");
            return false;
        }
        return true;
    }

    function checkPasswordValidation() {
        var password = document.getElementById("password").value;
        var pattern = /^(?=.*[!@#$%^&*-])(?=.*[0-9]).{8,}$/;

        if (!pattern.test(password)) {
            $("#passwordValidation").html("*Password must be at least 8 characters and contain 1 number and 1 special character.");
            return false;
        }

        return true;
    }

    function checkNameValidation() {
        const inputField = document.getElementById("full_name");
        const inputValue = inputField.value;
        var pattern = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

        if (pattern.test(inputValue)) {
            $("#nameValidation").html("Name should not contain digits or special characters");
            return false;
        }
        return true;
    }

    $('#getBornTodayButton').click(function () {
        var birthdate = $('#birthdate').val();

        if (!birthdate) {
            alert("Birthdate field is required");
            return;
        }

        alert("Wait a second.");

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
</script>
@endsection

