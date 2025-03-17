<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $topicName }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #000000;
            background-color: #ffffff;
        }
        
        h1 {
            text-align: center;
            color: #000000;
            font-size: 20px;
            font-weight: bold;
        }
        
        h4 {
            font-size: 12px;
            color: #000000;
            font-weight: bold
        }
        p {
            font-size: 12px;
            color: #000000;
        }
        span {
            font-weight: normal;
            color: #000000;
        }
        
        .content {
            color: #000000;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666666;
            border-top: 1px solid #cccccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Reviewer for  {{ $topicName }}</h1>
    <div class="content">
        {!! $reviewerContent !!}
    </div>
    <div class="footer">
        Generated on {{ date('F j, Y') }} | MEECO
    </div>
</body>
</html>