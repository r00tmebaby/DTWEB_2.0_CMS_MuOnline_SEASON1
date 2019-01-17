<?PHP
if (eregi("includes/clean_var.php", $_SERVER['SCRIPT_NAME'])) { die ("Access Denied!"); }
function clean_var($var=NULL) {
$newvar = @preg_replace('/[^a-zA-Z0-9\_\-\.]/', '', $var);
if (@preg_match('/[^a-zA-Z0-9\_\-\.]/', $var)) { }
return $newvar;
}
?>
