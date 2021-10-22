<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 用于ajax请求 -->
    <meta id="token" name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>

<body>
    <form action="./test8" method="post">
        @csrf
        <input type="text" name="name">
        <!-- 通过指令显示表单错误 -->
        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <input type="text" name="age">
        <button type="submit">提交</button>
    </form>
    <!-- 显示表单所有错误 -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <script>
        let token = document.querySelector('#token').attributes.content.value;
        fetch('./test8', {
            headers: {
                'X-CRSF-TOKEN': token,
                'Accept': 'application/json' // 通过头指定，获取的数据类型是JSON
            }
        }).then(resp => {
            console.log(resp);
            return resp.json();
        }).then(res => {
            console.log(res);
        })
    </script>
</body>

</html>