<meta charset="utf-8">
<style>
b {
    color: blue;
}
</style>
<?
if(isset($_POST['keyword'])){
	$keyword=$_POST['keyword'];
	$limit=$_POST['limit'];
}
else{
	$keyword="Leather Chair";
	$limit=10;
}
?>
<form action="?" method="post">
<table>
<tr><td colspan="2" align="center"><h1>Prodect Search</h1></td></tr>
  <tr><td>Input Your keyword(s):</td><td><input type="text" name="keyword" id="keyword" value="<?=$keyword?>"></td></tr>
 <tr><td>Prodect Search Limit:</td><td><input type="text" name="limit" id="limit" value="<?=$limit?>" size="5"></td></tr>
   <tr><td></td><td><input type="submit" value="Search" name="submit"></td></tr>
   </table>
</form>
<?
if($_POST[submit]=="Search"){
$txt_input="product.txt";
 echo "File default input:<a href='product.txt' target='_blank'>product.txt</a>";
$pattern=$keyword;
$p_mark=0;
$myfile = fopen($txt_input, "r") or die("Unable to open file!");
if($myfile) {
        $file = fread($myfile,filesize($txt_input));
	fclose($myfile);
}
echo "<hr>";

$arr_txt=explode("\n", $file);
$N=count($arr_txt);
//echo $N;
$i=1;
for($k=0;$k<$N;$k++)
 {
$arr_txt[$k]."<br>";

$arr_pattern=array();
$arr_pattern=explode(" ", $pattern);
if($arr_pattern[1]=="")$arr_pattern[1]=$arr_pattern[0];
$index1=BoyerMoore($arr_txt[$k], $arr_pattern[0]);
if($index1>="0")
	 {
$p_mark=$index1;
//echo $arr_txt[$k]."->$index1";
$arr_pattern[0]=str_replace($arr_pattern[0],"<b>".$arr_pattern[0]."</b>",$arr_txt[$k]);
	 }
$index2=BoyerMoore($arr_txt[$k], $arr_pattern[1]);
if($index2>="0")
	 {
if($index1<"0"){
$word[$i]=str_replace($arr_pattern[1],"<b>".$arr_pattern[1]."</b>",$arr_txt[$k]);
$m_num="1";
$n_len="999";
}
else{
$word[$i]=str_replace($arr_pattern[1],"<b>".$arr_pattern[1]."</b>",$arr_pattern[0]);
$m_num="2";
$n_len=$index2-$index1;
}
$list[$i][0]=$i;
$list[$i][1]=$n_len;
$list[$i][2]=$p_mark;
$list[$i][3]=$m_num;
$list[$i][4]=$word[$i];
$list[$i][5]=$k;
//echo "$i=$word[$i]->$m_num->$p_mark->$n_len <br>";
$i++;
	 }

 }
//echo "<hr>";
//echo print_r($list);
 foreach ($list as $key => $val) {
$j=$val[3];
$data[$j][]=$val[0];
 }
//echo "<hr>";
//////////////////////////////////////////////////
$temp=array();
 for($i=0;$i<=count($data[2])-1;$i++){
$key=$data[2][$i];
$k2=$list[$key][2];
$k0=$list[$key][0];
//echo $k0."-".$k2."<br>";
$temp[$k0]=$k2;
 }
 asort($temp);
 foreach ($temp as $key => $val) {
	 $tt1[]=$key;
	     //echo "$key = $val\n";
}
//echo print_r($tt1);
//////////////////////////////////////////////////
//////////////////////////////////////////////////

$temp=array();
 for($i=0;$i<=count($data[1])-1;$i++){
$key=$data[1][$i];
$k2=$list[$key][2];
$k0=$list[$key][0];
//echo $k0."-".$k2."<br>";
$temp[$k0]=$k2;
 }
 asort($temp);
 foreach ($temp as $key => $val) {
	 $tt2[]=$key;
	     //echo "$key = $val\n";
}
//////////////////////////////////////////////////
//echo "<hr>";
//echo print_r($tt2);
if($tt1!='' && $tt2!=""){
$tt=array_merge($tt1, $tt2);
}
else{
$tt=$tt1;
}

echo "<hr>";
//echo print_r($tt);
if($tt){
echo "<table>";
echo "<tr><th>No</th><th>ID</th><th>ชื่อสินค้า</th><th>จำนวนคำหลักที่ถูกจับคู่</th><th>ตำแหน่งการจับคู่ของหลักคำแรก</th><th>ระยะห่างที่น้อยที่สุดระหว่างคำหลักคู่ใดๆ</th></tr>";
//======================================
 for($i=0,$n=1;$i<=$limit-1;$i++,$n++){
	 $piont=$tt[$i];
	 echo "<tr><td>$n</td><td>".$list[$piont][5]."</td><td>".$list[$piont][4]."</td><td>".$list[$piont][3]."</td><td>".$list[$piont][2]."</td><td>";
	 if($list[$piont][1]!="999"){
		 echo $list[$piont][1];
	 }
	 else{
echo "-";
	 }
	 
	 echo "</td></tr>";
 }
//======================================
echo "</table>";
}
}
?>
<?
function BoyerMoore($text, $pattern) {
    $patlen = strlen($pattern);
    $textlen = strlen($text);
    $table = makeCharTable($pattern);
    for ($i=$patlen-1; $i < $textlen;) { 
        $t = $i;
        for ($j=$patlen-1; $pattern[$j]==$text[$i]; $j--,$i--) { 
            if($j == 0) return $i;
        }
        $i = $t;
        if(array_key_exists($text[$i], $table))
            $i = $i + max($table[$text[$i]], 1);
        else
            $i=$i+$patlen;
    }
    return -1;
}
function makeCharTable($string) {
	//echo "-->".$string;
    $len = strlen($string);
    $ch_table = array();
    for ($i=0; $i < $len; $i++) { 
        $ch_table[$string[$i]] = $len - $i-1 ; 
    }
		//	echo print_r($ch_table)."<hr>";

  return $ch_table;

}

function radix_sort(&$input)
{
    $temp = array();
	$len = count($input);
	// initialize with 0s
    $temp = array_fill(1, $len-1, 0);
    foreach ($input as $key => $val) {
    	$temp[$val]++;
    }
 
    $input = array();
    foreach ($temp as $key => $val) {
	if ($val == 1) {
		$input[] = $key;
	} else {
		while ($val--) {
			$input[] = $key;
		}
	}
    }
}
?>