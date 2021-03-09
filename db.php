<?php
function db_control($request_sql,$db_host,$db_name,$db_user,$db_pass){
    $dsn= 'mysql:dbname='.$db_name.';host='.$db_host;
    try{
        $dbh = new PDO($dsn, $db_user, $db_pass);
        if($request_sql['pa']=="s"){
            $sql_sp=[];
            $sql_sp2=[];
            $num = 0;
            foreach (array_keys($request_sql['da']) as $row){
                $sql_sp[]=$row;
            }
            foreach (array_keys($request_sql['da']) as $row){
                $sql_sp2[]=':'.$row;
            }
            $query = $dbh->prepare("INSERT INTO ".$request_sql['dt']." (".implode(',', $sql_sp).") VALUES (".implode(',', $sql_sp2).")");
            while ($num < count($request_sql['da'])){
                $query->bindValue($sql_sp2[$num], $request_sql['da'][$sql_sp[$num]], PDO::PARAM_STR);
                $num+=1;
            }
            $query->execute();
            if($query -> errorInfo()[0]==00000){
                return 200;
            }else{
                return$query -> errorInfo();
            }
        }else if($request_sql['pa']=="u"){
            $sql_sp=[];
            $sql_sp2=[];
            $sql_sp3=[];
            foreach (array_keys($request_sql['da']) as $row){
                $sql_sp[]=$row.'=:'.$row;
            }
            foreach (array_keys($request_sql['da']) as $row){
                $sql_sp2[]=':'.$row;
            }
            foreach (array_keys($request_sql['da']) as $row){
                $sql_sp3[]=$row;
            }
            $query=$dbh->prepare("UPDATE ".$request_sql['dt']."  SET ".implode(',', $sql_sp)." WHERE ".array_keys($request_sql['po'])[0]." = :".array_keys($request_sql['po'])[0]);
            $query->bindValue(":".array_keys($request_sql['po'])[0], $request_sql['po'][array_keys($request_sql['po'])[0]], PDO::PARAM_STR);
            $num=0;
            while ($num < count($request_sql['da'])){
                $query->bindValue($sql_sp2[$num], $request_sql['da'][$sql_sp3[$num]], PDO::PARAM_STR);
                $num+=1;
            }
            $query->execute();
            if($query -> errorInfo()[0]==00000){
                return 200;
            }else{
                return$query -> errorInfo();
            }
        }else if($request_sql['pa']=="g"){
            if($request_sql['st']=="all"){
                $query=$dbh->prepare("SELECT ".implode(',', $request_sql['da'])." FROM ".$request_sql['dt']);
                $query->execute();
                if($query -> errorInfo()[0]==00000){
                    return $query -> fetchAll(PDO::FETCH_ASSOC);
                }else{
                    return$query -> errorInfo();
                }
            }else if($request_sql['st']=="se"){
                $query=$dbh->prepare("SELECT ".implode(',', $request_sql['da'])."  FROM ".$request_sql['dt']." WHERE ".array_keys($request_sql['po'])[0]." = :".array_keys($request_sql['po'])[0]);
                $query->bindValue(":".array_keys($request_sql['po'])[0], $request_sql['po'][array_keys($request_sql['po'])[0]], PDO::PARAM_STR);
                $query->execute();
                if($query -> errorInfo()[0]==00000){
                    return $query -> fetchAll(PDO::FETCH_ASSOC);
                }else{
                    return$query -> errorInfo();
                }
                
            }else{
                return 100;
            }
        }else if($request_sql['pa']=="d"){
            $query=$dbh->prepare("DELETE FROM ".$request_sql['dt']." WHERE ".array_keys($request_sql['po'])[0]." = :".array_keys($request_sql['po'])[0]);
            $query->bindValue(":".array_keys($request_sql['po'])[0], $request_sql['po'][array_keys($request_sql['po'])[0]], PDO::PARAM_STR);
            $query->execute();
            if($query -> errorInfo()[0]==00000){
                return 200;
            }else{
                return$query -> errorInfo();
            }
        }else{
            return 100;
        }
        


    }catch(PDOException $e){
        return "500".$e->getMessage();
        die();
    }
    $dbh = null;
}

//db_control(リクエスト配列,DBホスト名,DB名,DBユーザー名,DBパスワード)

//200:成功
//100:値が足りません
//500:接続不可

//エラー処理が行き届いていない部分や、データーベースのテーブル名やフィールド名は信用された値としてエスケープしていない側面があります。
//事前に指定に使う配列に不備がないかの確認をお願いします

