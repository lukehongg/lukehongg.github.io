# RET Sled
> RET을 연쇄적으로 호출하여 스택상의 위치를 계속 높은 주소로 옮기며 쉘코드를 실행하는 방법
\x90 (NOP)를 만나면 no operation을 하는 특성을 이용하여 쉘코드에 접근하는 방식

    [HOW]
    1. buf의 시작주소, rbp + 4(sfp)를 16진수로 받고
    2. p/d buf 시작주소 - rbp + 4 해서 총 buf~ret전까지 얼마만큼의 공간이 있는지 ( NOP + shellcode를 입력할 수 있는 공간) 확인한다. -> 공간을 N이라고하자
    3. payload 작성
    ./bof `python -c "print '\x90' * N-25 + 'SHELLCODE'+'NOP가 들어있는 주소 little endian방식'"`
    *(N-25 == Shellcode공간확보)
    standard input 방식 = (python -c "print '\x90' * N-25 + 'SHELLCODE'+'NOP가 들어있는 주소 little endian방식'";cat) | ./bof
    SHELLCODE(25bytes): \x31\xc0\x50\x68\x2f\x2f\x73\x68\x68\x2f\x62\x69\x6e\x89\xe3\x50\x53\x89\xe1\x89\xc2\xb0\x0b\xcd\x80

# SFP_Overflow
> 함수의 에필로그의 leave 와 ret 단계 && sfp의 1바이트의 overflow를 이용한 쉘코드 실행방식

> SFP영역에서 최소 1바이트의 overflow발생        << SFP Overflow가 필요하기 때문   
메인 함수 이외의 서브 함수가 필요                 << Leave/Ret이 2번필요하기 때문

    ** pop eip => esp가 가르키고 있는 주소가 가진 주소값을 eip에 넣는다
    jmp eip -> esp의 값의 주소(현재 pop된 eip 값)으로 jump

    1. 서브함수에서 strncpy(buf, src, buf.length + 1)로 sfp 자리에 1바이트의 overflow를 만든다.
    이때 overflow가 된 값은 서브함수의 buf 주소 - 4 값이 되어야 한다 (pop ebp하면 esp가 +4 되기때문)  SFP에 들어있는 값 = buf시작주소 - 0x4
    1. ebp는 서브함수의 buf 시작주소 - 4를 가리키고 있으며 main 함수의 에필로그 단계를 거친다.
    2-1) mov esp, ebp ->  buf 시작주소 - 4를 가리키고 있는 ebp의 값을 esp에 넣어서 둘다 buf 시작주소 - 4를 가르킴
    2-2) pop ebp -> SFP에 들어있는 주소를 ebp가 가리키고, esp=esp+4가 된다. 즉, esp는 buf를 가리킨다.
    2-3) pop eip -> esp가 가리키고있는 주소가 가진 값 ( esp가 가르키는 주소 = buf의 시작주소니까 buf의 첫 4바이트 값)을 eip에 넣어주고
    2-4) jmp eip -> 해당 주소값으로 점프한다. 이떄 buf의 첫 4bytes에 shellcode의 주소가 있으면 쉘코드 실행


# GOT_Overwrite
> 공유라이브러리에 있는 함수를 사용할 때 
PLT: 프로그램 내에서 함수가 쓰인 곳의 주소(임시) 
GOT: 공유라이브러리에 해당 함수를 참조해주는 주소
run 하고 p system -> 공유 라이브러리에 system()함수의 주소 복사
x/2i <p system 주소> ->> system의 GOT 주소
-> GOT에 p system으로 찾은 system()의 함수 주소를 overwrite해서 결국 PLT -> GOT(system)을 실행

> 이때 PLT가 실행된 함수의 인자를 "/bin/sh"를 넣어주면 끝 -> puts의 인자를 /bin/sh의 값이 있는 걸로 넣어주면됨
breakpoint를 GOT를 overwrite하는 function 전에 잡기 (puts 함수 실행 전)

# RTL
> return address (system 함수 주소) + 4 바이트 더미값(system 함수 stack의 return address) + argument채우기(/bin/sh이 저장된 주소)
argument채우기(/bin/sh이 저장된 주소) --> find (p system으로 찾은 주소), +99999999, "/bin/sh"
> SFP주소까지 채우기: main의 프롤로그 다음epb-0xOO << 16진수->10진수 변환한 만큼 + 4(SFP)를  \x90값 넣어주기

# Heap Overflow
> 메모리 동적할당 (malloc)시 heap 메모리 사용
use after free -> malloc free 후 이전과 같은 size(bytes)만큼의 할당(malloc)을 받으면 
이전 malloc에서 사용했던 data가 남아있음(즉, 다시 같은 크기의 할당을 받으면 
CPU에서 같은/비슷한 주소를 할당해주기 때문에 DATA가 남아있는 것을볼 수 있음)
info proc mappings -> heap 주소 확인
x/100wx heap주소

    이러한 특성을 이용하여 메모리할당 받은 변수를 free한 후 다른 변수로 같은 bytes 크기만큼의 메모리 할당을 받았을 때 free했던 변수와 같은 주소를 할당받으며 이미 free한 첫 번째 변수를 그대로 접근(사용)할 수 있다. 


# Fake ebp
> 새로운 Stack(함수호출)이 생겼을 때 sfp에 이전 함수의 ebp 값이 저장된다. 그리고 프롤로그와 에필로그를 거치며 함수호출이 끝났을 때 sfp에 있는 주소값으로 ebp가 설정되어 돌아간다. 

> Fake ebp는 buffer overwrite를 이용하여 sfp에 저장된 주소값, ret에 저장된 주소값을 조정하고 shellcode가 담긴 주소와 NOP를 사용하여 쉘명령어를 실행시키는 공격 방식이다.

    SFP -> buffer[0] – 4
    RET -> 해당 함수의 leave 함수 주소값(disas f 사용)
    Buf -> 첫 4bytes는 buf[4]의 주소값을, 나머지는 shellcode + NOP

    함수의 에필로그 과정에서 pop ebp를 했을 때 esp가 + 4 되는 것을 고려하여 sfp에 buffer[0]-4 주소값을 넣어주고 pop ebp를 했을 때 esp가 ret을 가르키게 된다. 이때 pop eip를 했을 때 ret안에 있던 주소(leave)가 다시 실행된다.

    두번째 leave가 실행될 때 ebp -> buf-4, esp -> ret을 가르키고 있다. Mov esp, ebp와 pop ebp를 거치면서 ebp는 buf-4가 가르키고 있던 곳으로 접근하며 esp는 buf[0]을 가르킨다. 그리고 다시 pop eip를 했을 때 buffer[0]~[3]의 주소값으로 jump를 하게되는데 이때 buffer[0]~[3]에는 buffer[4]의 주소값을 넣어주었기에 buffer[4]로 jump하게되고 쉘코드가 실행된다.
