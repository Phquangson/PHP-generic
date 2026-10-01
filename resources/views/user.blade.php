<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Test</title>
    <link rel="stylesheet" href="{{ asset('styles/user.css') }}">
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>User Test</h1>
            <p>
                Test dữ liệu trước và sau khi xử lý.
            </p>
        </div>

        <div class="card">
            <h2 class="card-title">
                Input Data
            </h2>

            <div class="form-group">
                <label>
                    Name
                </label>
                <input type="text" id="name" placeholder="Nhập name..." value="   Quang   ">
            </div>

            <div class="form-group">
                <label>
                    Age
                </label>
                <input type="text" id="age" placeholder="Nhập age..." value="25">
            </div>

            <div class="form-group">
                <label>
                    Email
                </label>
                <input type="text" id="email" placeholder="Nhập email..." value=" TEST@GMAIL.COM ">
            </div>

            <div class="form-group">
                <label>
                    Description
                </label>
                <textarea id="description" placeholder="Nhập description...">   Đây là description test.   </textarea>
            </div>


            <div class="button-wrapper">
                <button type="button" onclick="testUser()">
                    TEST User
                </button>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">
                Result
            </h2>

            <div class="result-grid">
                <div class="result-box">
                    <div class="result-header">
                        Before User
                    </div>
                    <pre id="before">Chưa có dữ liệu...</pre>
                </div>

                <div class="result-box">
                    <div class="result-header">
                        After User
                    </div>
                    <pre id="after">Chưa có dữ liệu...</pre>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/user.js') }}"></script>
    
</body>

</html>