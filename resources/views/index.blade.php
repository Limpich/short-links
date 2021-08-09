<html>
<head>
    <title>Форма заявки</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <style>
        .center {
            align-items: center;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .border {
            width: fit-content;
            border-radius: 5px;
            border-width: 1px;
            border-style: solid;
        }

        .mg {
            margin: 8px;
        }

        .error {
            font-size: 12px;
            color: red;
        }

        .field {
            clear: both;
            text-align: right;
        }

        label {
            float: left;
            margin-right: 8px;
        }

        .font {
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
        }

        .title {
            font-size: 36px;
        }
    </style>
</head>

<body>

<div class="center">
    <form class="border" action="/create" method="POST">
        @csrf
        <div>
            <h3 class="font title mg">Сократить ссылку</h3>
        </div>

        <div class="field mg">
            <label class="font" for="url">Ссылка</label>
            <input id="url" type="text" name="url" @if (isset($url)) value="{{ $url }}" @endif/>
        </div>

        @if ($errors->has('url'))
            <p class="error">{{ $errors->first('url') }}</p>
        @endif

        @if (isset($result))
            <div>
                <a href="{{ $result }}">{{ $result }}</a>
            </div>
        @endif

        <button class="mg" type="submit">Сократить!</button>
    </form>
</div>

</body>
</html>
