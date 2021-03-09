<?php 
###########################################
########### 로그인 처리 페이지 ###############
###########################################

// post방식 전달값 변수에 할당하기 ///

$mid = $_POST["mid"];
$mpw = $_POST["mpw"];

//echo "아이디: $mid / 비번: $mpw";

# 순서상 먼저 아이디가 있는지 검사
# 있다면
# 해당 아이디로 DB에 쿼리하여 비밀번호를 가져온다!
# 비밀번호를 입력한 것과 비교하여 검사 결과를 낸다!

# 어떻게 비밀번호를 비교할 것인가???
# DB에 입력된 비번은 해시암호화된 비번이므로
# 단순 문자 비교를 하면 절대로 같을 수 없다!!!

# php 에서 해시암호화 문자 비교 메서드가 있음!
# password_verify(입력된비번, DB해시비번)
# 결과: 만약 같으면 true, 같지않으면 false를 리턴해준다!
# -> 결과를 값으로 찍으면 true일때 1이 나오고, false일때 null값을 준다!
# -> 이것을 if문으로 처리할때 
#   1은 true로 해석되고 
#   null값은 false로 해석된다!

// 쿼리문 만들기
$sql = "SELECT `mid`,`mpw`,`name`,`auth` FROM `member` WHERE `mid` = '$mid'";

//echo " / 쿼리문 : ".$sql;

# DB연결하기
include "dbcon.inc";

# 쿼리 날리기
# $conn->query(쿼리문)
$res = $conn->query($sql);

# 1. 레코드 유무 판별
# $res->num_rows 결과레코드 개수를 담은 속성
$cnt = $res->num_rows;

//echo " / 레코드개수: $cnt";

# 2.레코드 개수가 1인 경우 비밀번호 비교하기
if($cnt){
    
    # $res->fetch_assoc() : 
    # 결과집합의 레코드를 이름으로 가져온다!
    $row = $res->fetch_assoc();

    //echo " / DB비밀번호 : ".$row["mpw"];
    
    # 비번비교하기
    # password_verify(입력된비번, DB해시비번)
    
    //echo " / 비번검증 : ".
    //    password_verify($mpw,$row["mpw"]);
    
    # 비번검증
    $allow = password_verify($mpw,$row["mpw"]);
    ###################################
    # 비번검증 결과 통과시 세션을 시작하고 #
    # 세션변수에 개인정보할당
    if($allow){
        
        # 세션연결하기 : 사용자의 로그인 상태를 기록함!
        session_start();// 세션이 시작됨!
        # DB에서 가져온 값 세션변수에 할당하기!
        # $_SESSION[변수명] - 서버세션변수에 기록!
        
        # 사용자 아이디
        $_SESSION["mid"] = $mid;
        # 사용자 이름
        $_SESSION["name"] = $row["name"];
        # 사용자 권한
        $_SESSION["auth"] = $row["auth"];
        
        # 성공시
        echo "ok";
        
    } /////// if //////////////////////
    
    # 비밀번호 불통과시 ##################
    else{
        
        echo "again";
        
    } /////// else /////////////////////
    
    
    
} ////////// if문 //////////////////////

# 3.레코드가 없는 경우
else{
    
    echo "no";
    
} ////////// else문 /////////////////////





?>