<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  </head>
  <body>
    <canvas id="mainCanvas"></canvas> 
    <script type="text/javascript">
        let body = document.body;
        body.style.width = '100%';
        body.style.height = '100%';
        body.style.margin = '0';
        body.style.padding = '0';

        // canvas의 크기는 width=100%, height는 width의 1.5 비율로 사용할 예정입니다.
        let canvas = document.getElementById('mainCanvas');
        canvas.width = body.clientWidth;
        canvas.height = Math.min(body.clientWidth * 1.5, body.clientHeight);
        canvas.style.backgroundColor = '#000000';

        // 실제 화면을 그릴 비율입니다.
        // context를 이용하여 그림을 그릴 때 화면 넓이가 400, 높이는 넓이*1.5배라는 계산하에 작업할 예정입니다.
        const rWidth = 400;
        const rHeight = 400 * 1.5; // 600

        // 실제 canvas 넓이와 그림 비율이 맞지 않기 때문에 scale을 변경해줍니다.
        let context = canvas.getContext('2d');
        let ratioX = canvas.width / rWidth;
        let ratioY = canvas.height / rHeight;
        context.scale(ratioX, ratioY);
    </script>
  </body>
</html>
