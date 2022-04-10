<?php 
// error_reporting(0);
error_reporting(E_ALL);
include"database.php";
function debug($varr)
{
    file_put_contents('temp_debug.txt', print_r($varr, true));
}
function free($query)
{
    global $db;
    $sql = mysqli_query($db, $query);
    $check = mysqli_num_rows($sql);
    $val = array();
    if ($check != 0) {
        while ($data = mysqli_fetch_assoc($sql)) {
            $val[] = $data;
        }
    }
    return $val;
}
function create($table = "", $post)
{
    global $db;
    // array_unshift($post, NULL); //id : pk
    $data = "'" . implode("','", $post) . "'";
    $sql = mysqli_query($db, "insert into $table values(null,$data)");
    debug("insert into $table values(null,$data)");
    return $sql ? true : false;
}
function update($table = "", $post, $id)
{
    global $db;
    array_shift($post); //id : pk
    $jumlah = count($post);
    $num = 1;
    $contt = '';
    foreach ($post as $key => $val) {
        $contt .= $key . "='" . $val . "'";
        $num < $jumlah ? $contt .= "," : $contt .= " where " . $id;
        $num++;
    }
    $sql = mysqli_query($db, "Update " . $table . " set " . $contt);
    return $sql ? true : false;
}
function delete($table = "", $id = "")
{
    global $db;
    $sql = mysqli_query($db, "delete from $table where id='$id'");
    return $sql ? true : false;
}
function dataset($table = "", $kondisi = "")
{
    global $db;
    $val = array();
    $sql = mysqli_query($db, "select * from $table $kondisi");
    $check = mysqli_num_rows($sql);
    $val = array();
    if ($check != 0) {
        while ($data = mysqli_fetch_assoc($sql)) {
            $val[] = $data;
        }
    }
    return $val;
}
function single($table = "", $id = "")
{
    global $db;
    $sql = mysqli_query($db, "select * from " . $table . " where id='" . $id . "'");
    $check = mysqli_num_rows($sql);
    if ($check != 0) {
        while ($data = mysqli_fetch_assoc($sql)) {
            $val = $data;
        }
    }
    return $val;
}
function cleansingArrayString($string){
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    return strtolower(preg_replace('/-+/', '-', $string));
}
?>