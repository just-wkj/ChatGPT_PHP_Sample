<?php
/**
 * @author: justwkj
 * @date: 2022/12/9 09:33
 * @email: justwkj@gmail.com
 * @desc:
 */


$TEXT = $_POST['message'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT APIdemo</title>
    <style>
        .form-body {
            display: flex;
            flex-direction: column;
            justify-content: center; /* 水平居中 */
            align-items: center; /* 垂直居中 */
        }

        .form-body .btn {
            width: 130px;
            background-color: #43A67C;
            color: #F0F0F0;
        }
    </style>
</head>
<body>

<div>
    <h1 style="text-align: center">ChatGPT</h1>
</div>
<div class="form-body">
    <form class="form" action="" method="post">
        <label for=message>请输入问题描述: </label><br/>
        <textarea name="message" rows="10" cols="40"></textarea><br/>
        <input class="btn" type="submit"/>
    </form>
    <div class="result">
        <?php
        //<!-- https://github.com/nstation/ChatGPT_PHP_Sample/blob/master/ChatGPT_PHP_Sample.php -->
        if (!empty($TEXT)) {
            $log = vsprintf("%s %s: %s".PHP_EOL, [
                date('Y-m-d H:i:s'),
                $_SERVER['REMOTE_ADDR'],
                $TEXT,
            ]);
            file_put_contents('chatgpt.txt', $log, FILE_APPEND);
            echo "<br>问题描述:" . $TEXT . "<br>";
            echo "<h1>-----结果-------</h1>";
            $API_KEY = '你的APIKEY';

            $header = [
                'Authorization: Bearer ' . $API_KEY,
                'Content-type: application/json',
            ];

            $params = json_encode([
                'prompt' => $TEXT,
                'model' => 'text-davinci-003',
                'temperature' => 0.5,
                'max_tokens' => 4000,
                'top_p' => 1.0,
                'frequency_penalty' => 0.8,
                'presence_penalty' => 0.0,
            ]);


            $curl = curl_init('https://api.openai.com/v1/completions');
            $options = [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_RETURNTRANSFER => true,
            ];
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);

            $httpcode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

            if (200 == $httpcode) {
                $json_array = json_decode($response, true);
                $choices = $json_array['choices'];
                foreach ($choices as $v) {
                    echo $v['text'] . '<br />';
                }
            }
        }

        ?>
    </div>
</div>


</body>
</html>
