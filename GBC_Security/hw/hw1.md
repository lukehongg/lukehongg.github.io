# hw1 (crackme 1~3)

> crackme 1
답: 5274

풀이

1) cmp을 사용하여 input과 $ebp -4 값을 비교한다.
2) $ebp -4 주소값 안에 있는 값을 확인한다 
(x/w $ebp -4 사용)
3) 해당 주소값 안에 있는 값을 input으로 주면 input과 ebp-4 값이 같다는 결과가 FLAG register에 저장됨
4) input을 x/w $ebp -4 사용했을 때 출력되는 값을 10진수로 입력해준다.


> crackme 2
답: 338724

풀이
1) input을 저장하는 주소값은 ebp -4이며 이 값을 eax에 저장한 후 ebp - 0xc의 주소값에 있는 값과 비교한다.
2) x/w $ebp-0xc 사용으로 ebp - 0xc에는 0x52b24(338724)가 저장되어 있는 것을 확인할 수 있다.
3) input을 338724로 주어진다면  해결.


> crackme 3
답: 338724

풀이
1) 입력값을 test 함수의 첫번째 argument로 주어지며
2) test 함수내에서는 첫 번째 parameter와 0x52b24 (338724) 값을 cmp한다.
3) input을 338724로 주어진다면 test의 첫 번째 parameter 는 338724이고 이는 0x52b24주소와 cmp했을 때 같다는 결과가 FLAG register에 저장된다
