<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Test</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 40px 20px;

            font-family: Arial, sans-serif;

            background: #f3f4f6;
            color: #1f2937;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);

            margin-bottom: 20px;
        }

        .card-title {
            margin: 0 0 20px;
            font-size: 18px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        label {
            display: block;
            margin-bottom: 7px;

            font-size: 14px;
            font-weight: 600;
        }

        input,
        textarea,
        select {
            width: 100%;

            padding: 11px 13px;

            border: 1px solid #d1d5db;
            border-radius: 7px;

            font-size: 14px;

            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #2563eb;
        }

        .button-wrapper {
            margin-top: 25px;
        }

        button {
            width: 100%;

            padding: 12px;

            border: none;
            border-radius: 7px;

            background: #2563eb;
            color: white;

            font-size: 15px;
            font-weight: 600;

            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .result-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .result-box {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .result-header {
            padding: 12px 15px;

            background: #f9fafb;

            font-size: 14px;
            font-weight: 700;
        }

        pre {
            margin: 0;
            padding: 18px;

            min-height: 180px;

            background: #111827;
            color: #e5e7eb;

            font-size: 13px;
            line-height: 1.6;

            overflow-x: auto;
        }

        .type {
            display: inline-block;

            padding: 3px 8px;

            border-radius: 5px;

            background: #e5e7eb;

            font-size: 12px;
            font-weight: 600;
        }

        @media (max-width: 700px) {
            .result-grid {
                grid-template-columns: 1fr;
            }

            body {
                padding: 20px 10px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>User Test</h1>

        <p>
            Test dữ liệu trước và sau khi xử lý.
        </p>
    </div>


    <!-- INPUT -->

    <div class="card">

        <h2 class="card-title">
            Input Data
        </h2>

        <div class="form-group">

            <label>
                Name
                <span class="type">string</span>
            </label>

            <input
                type="text"
                id="name"
                placeholder="Nhập name..."
                value="   Quang   "
            >

        </div>


        <div class="form-group">

            <label>
                Age
                <span class="type">int</span>
            </label>

            <input
                type="text"
                id="age"
                placeholder="Nhập age..."
                value="25"
            >

        </div>


        <div class="form-group">

            <label>
                Email
                <span class="type">email</span>
            </label>

            <input
                type="text"
                id="email"
                placeholder="Nhập email..."
                value=" TEST@GMAIL.COM "
            >

        </div>


        <div class="form-group">

            <label>
                Description
                <span class="type">string</span>
            </label>

            <textarea
                id="description"
                placeholder="Nhập description..."
            >   Đây là description test.   </textarea>

        </div>


        <div class="button-wrapper">

            <button type="button" onclick="testUser()">
                TEST User
            </button>

        </div>

    </div>


    <!-- RESULT -->

    <div class="card">

        <h2 class="card-title">
            Result
        </h2>

        <div class="result-grid">

            <!-- BEFORE -->

            <div class="result-box">

                <div class="result-header">
                    Before User
                </div>

                <pre id="before">Chưa có dữ liệu...</pre>

            </div>


            <!-- AFTER -->

            <div class="result-box">

                <div class="result-header">
                    After User
                </div>

                <pre id="after">Chưa có dữ liệu...</pre>

            </div>

        </div>

    </div>

</div>


<script>

    async function testUser() {

        const data = {

            name: document.getElementById('name').value,

            age: document.getElementById('age').value,

            email: document.getElementById('email').value,

            description: document.getElementById('description').value

        };


        document.getElementById('before').textContent =
            JSON.stringify(data, null, 4);


        try {

            const response = await fetch('/user/sanitize', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'Accept': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'

                },

                body: JSON.stringify(data)

            });


            const result = await response.json();


            // Nếu User báo lỗi

            if (!result.success) {

                document.getElementById('after').textContent =
                    JSON.stringify({
                        error: result.message
                    }, null, 4);

                return;
            }


            // Hiển thị dữ liệu sau khi User xử lý

            document.getElementById('after').textContent =
                JSON.stringify(result.after, null, 4);


        } catch (error) {

            document.getElementById('after').textContent =
                JSON.stringify({
                    error: 'Có lỗi xảy ra khi xử lý dữ liệu'
                }, null, 4);

        }

    }

</script>

</body>

</html>