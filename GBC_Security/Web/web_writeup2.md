# Command Injection

>Command Injection: Command에 임의의 명령어를 삽입하고 요청을 보내 웹 서버에서 해당 명령어를 실행시키는 공격

    만약 사용자가 IP 주소를 입력하면 웹 서버에서 shell_exec()함수를 이용하여 Ping 명령어를 실행하고 그 값을 return하는 웹 서버가 있을 때

    ip를 입력하고 뒤에 ; cat /etc/passwd라는 명령어를 같이 입력한다면 ping을 출력한 값이 return되고 이어서 /etc/passwd 값 또한 return 된다

>High

    input값에 | 혹은 &와 같은 입력값을 공백으로 대체하는 방어기술이 적용되어 있다.
    이때 적절한 우회조합을 만들어서 이러한 기술들을 우회하여 명령어를 만들 수 있는데

    예시)
    '| ' -> ''
    '||' -> ''
    '&'  -> ''
    만약 입력값에
    |&| netstat -a 를 입력한다면
    |한개만 쓰였을 때는 공백처리 하지 않지만 &는 공백처리하게되고 이어서 붙어있는 두번쨰 |도 역시 공백처리 되지 않기에 궁극적으로
    || netstat -a 가 완성/실행된다

# File Upload Vulnerable
> 공격코드가 담긴 파일을 웹 서버에 업로드하는 공격

>예시

    [hack.php]
    <?php
        system("쉘코드");
    ?>
    hack.php파일을 웹 서버에 업로드하고 해당 경로에서 이 파일을 확인할 때 쉘 코드가 실행된다

