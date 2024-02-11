<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{asset('css/myfunnybant.css')}}">
    <title>Document</title>
</head>
<body>  
        <div style="width: 58mm; heidht 40mm; font-size:8px; display: flex; justify-content: center; flex-direction: column; align-items: center; text-align: center">
            <img alt="testing" src={{ asset($img) }} width="100"/>
            {!! $data !!}
        </div>
</body>
</html>
